// Exercise engine: builds questions from the selected sets, checks answers in the
// chosen mode and runs sessions of a fixed length with a recap at the end.
//
// Modes:
//   type    - the character is shown, the student types its romaji / reading / meaning
//   choice  - same question, answered by picking one of four options
//   reverse - the romaji / meaning is shown, the student picks the character
const MODES = ['type', 'choice', 'reverse']
const LENGTHS = [0, 10, 20] // 0 = free play, no end

const GOOD_DELAY = 700
const BAD_DELAY = 1600

const exercise = {
  mode: readSetting('mode', MODES, 'type'),
  length: Number(readSetting('sessionLength', LENGTHS.map(String), '0')),
  question: null,
  asked: 0,
  good: 0,
  misses: [],
  // Questions left to replay when reviewing mistakes (null = random draw)
  queue: null,
  reviewLength: 0,
  locked: false,
}

const els = {
  character: document.querySelector('.character'),
  label: document.querySelector('.kanjitype'),
  form: document.querySelector('.forms'),
  input: document.getElementById('input'),
  choices: document.getElementById('choices'),
  feedback: document.getElementById('feedback'),
  progress: document.getElementById('progress'),
  progressText: document.getElementById('progressText'),
  progressFill: document.getElementById('progressFill'),
  recap: document.getElementById('recap'),
  play: document.getElementById('play'),
  good: document.querySelector('.good'),
  total: document.querySelector('.total'),
}

function readSetting(key, allowed, fallback) {
  try {
    const value = localStorage.getItem(key)
    return allowed.includes(value) ? value : fallback
  } catch (e) {
    return fallback
  }
}

function randomInt(max) {
  return Math.floor(Math.random() * max)
}

function shuffle(list) {
  const copy = list.slice()
  for (let i = copy.length - 1; i > 0; i--) {
    const j = randomInt(i + 1)
    ;[copy[i], copy[j]] = [copy[j], copy[i]]
  }
  return copy
}

// Lowercase, trimmed, single spaces; "to eat" also accepts "eat"
function normalize(text) {
  return text.toLowerCase().trim().replace(/\s+/g, ' ').replace(/^to /, '')
}

// "chi-ti-tsi" -> ["chi", "ti", "tsi"]
function alternatives(value) {
  return value.split('-').map((v) => v.trim()).filter((v) => v !== '')
}

// ---- Questions ---------------------------------------------------------------

// Every entry of a set as { key, char, answer } for the given facet:
// kana have one facet (romaji), kanji two (read, translate).
function entriesOf(type, facet) {
  if (myKana[type]) {
    return myKana[type]
      .filter((entry) => entry[2] === true)
      .map((entry) => ({ key: entry[0], char: entry[0], answer: entry[1] }))
  }

  return (myKanji[type] || []).map((entry) => ({
    key: entry.kanji,
    char: entry.kanji,
    answer: facet === 'read' ? entry.read : entry.translate,
  }))
}

// Favors characters the student has missed before (weakCharacters is
// injected server-side, empty for anonymous play)
function weightedPick(list, type, avoid) {
  const weights = (typeof weakCharacters !== 'undefined' && weakCharacters[type]) || {}
  const pool = []

  list.forEach((entry) => {
    if (list.length > 1 && entry.key === avoid) return
    const weight = 1 + Math.min(weights[entry.key] || 0, 4)
    for (let i = 0; i < weight; i++) pool.push(entry)
  })

  return pool[randomInt(pool.length)]
}

function facetFor(type) {
  if (myKana[type]) return 'romaji'
  return Math.random() < 0.5 ? 'read' : 'translate'
}

function drawQuestion() {
  if (exercise.queue) {
    return exercise.queue.shift()
  }

  const type = difficulty[randomInt(difficulty.length)]
  const facet = facetFor(type)
  const entry = weightedPick(entriesOf(type, facet), type, exercise.question?.key)

  return { type, facet, key: entry.key, char: entry.char, answer: entry.answer }
}

// Up to three wrong options from the same set, never equal to the right one
function distractors(question, pick) {
  const right = pick(question)
  const seen = new Set([right])
  const options = []

  shuffle(entriesOf(question.type, question.facet)).forEach((entry) => {
    const value = pick(entry)
    if (options.length < 3 && !seen.has(value)) {
      seen.add(value)
      options.push(value)
    }
  })

  return options
}

const FACET_LABELS = { read: 'lecture', translate: 'sens' }

// What to show and what counts as right for the current mode
function presentation(question) {
  const readable = (entry) => alternatives(entry.answer).join(' / ')

  if (exercise.mode === 'reverse') {
    return {
      prompt: readable(question),
      promptLang: question.facet === 'read' ? 'ja' : 'en',
      label: question.facet === 'romaji' ? 'quel caractère ?' : 'quel kanji ?',
      right: question.char,
      options: shuffle([question.char, ...distractors(question, (e) => e.char)]),
      optionLang: 'ja',
    }
  }

  return {
    prompt: question.char,
    promptLang: 'ja',
    label: FACET_LABELS[question.facet] || '',
    right: readable(question),
    accepted: alternatives(question.answer).map(normalize),
    options: shuffle([readable(question), ...distractors(question, readable)]),
    optionLang: question.facet === 'read' ? 'ja' : 'en',
  }
}

// ---- Flow --------------------------------------------------------------------

// Called whenever the selected sets, mode or length change
function startGame() {
  if (difficulty.length === 0) return

  exercise.asked = 0
  exercise.good = 0
  exercise.misses = []
  exercise.queue = null
  exercise.reviewLength = 0
  exercise.question = null
  updateScore()
  nextQuestion()
}

function startReview() {
  exercise.reviewLength = exercise.misses.length
  exercise.queue = shuffle(exercise.misses)
  exercise.asked = 0
  exercise.good = 0
  exercise.misses = []
  exercise.question = null
  updateScore()
  nextQuestion()
}

function nextQuestion() {
  const target = exercise.reviewLength || exercise.length
  if (target > 0 && exercise.asked >= target) {
    showRecap()
    return
  }

  exercise.question = drawQuestion()
  const view = presentation(exercise.question)
  exercise.view = view
  exercise.locked = false

  els.recap.classList.add('hidden')
  els.play.classList.remove('hidden')
  els.character.classList.remove('is-good', 'is-bad')
  els.character.textContent = view.prompt
  els.character.lang = view.promptLang
  els.character.classList.toggle('is-long', view.prompt.length > 3)
  els.label.textContent = view.label
  els.label.classList.toggle('hidden', view.label === '')
  els.feedback.textContent = ''
  els.feedback.className = ''

  const typing = exercise.mode === 'type'
  els.form.classList.toggle('hidden', !typing)
  els.choices.classList.toggle('hidden', typing)

  if (typing) {
    els.input.value = ''
    els.input.disabled = false
    els.input.maxLength = exercise.question.facet === 'romaji' ? 4 : 40
    els.input.lang = exercise.question.facet === 'read' ? 'ja' : 'en'
    els.input.focus()
  } else {
    renderChoices(view)
  }

  updateProgress()
}

function renderChoices(view) {
  els.choices.innerHTML = ''

  view.options.forEach((option, i) => {
    const button = document.createElement('button')
    button.type = 'button'
    button.className = 'choice'
    button.lang = view.optionLang
    button.dataset.value = option

    const key = document.createElement('kbd')
    key.textContent = i + 1
    const text = document.createElement('span')
    text.textContent = option

    button.append(key, text)
    button.addEventListener('click', () => answer(option))
    els.choices.appendChild(button)
  })
}

// Entry point of the typing form
function checkAnswerd() {
  if (exercise.locked) return
  const value = els.input.value
  if (value.trim() === '') return
  answer(value)
}

function answer(value) {
  if (exercise.locked || !exercise.question) return
  exercise.locked = true

  const view = exercise.view
  const correct = view.accepted
    ? view.accepted.includes(normalize(value))
    : value === view.right

  exercise.asked++
  if (correct) {
    exercise.good++
  } else if (!exercise.misses.some((q) => q.type === exercise.question.type && q.key === exercise.question.key && q.facet === exercise.question.facet)) {
    exercise.misses.push(exercise.question)
  }

  showFeedback(correct, view)
  updateScore()

  if (isLoggedIn) {
    sendScoreUpdate(correct, exercise.good, exercise.asked, exercise.question.type, exercise.question.key)
  }

  setTimeout(nextQuestion, correct ? GOOD_DELAY : BAD_DELAY)
}

function showFeedback(correct, view) {
  els.character.classList.add(correct ? 'is-good' : 'is-bad')
  els.input.disabled = true

  els.feedback.className = correct ? 'feedback-good' : 'feedback-bad'
  els.feedback.textContent = correct ? 'Bravo !' : 'Raté, la réponse était '
  if (!correct) {
    const right = document.createElement('strong')
    right.lang = view.optionLang
    right.textContent = view.right
    els.feedback.appendChild(right)
  }

  els.choices.querySelectorAll('.choice').forEach((button) => {
    button.disabled = true
    if (button.dataset.value === view.right) button.classList.add('is-right')
  })
}

function updateScore() {
  els.good.textContent = exercise.good
  els.total.textContent = exercise.asked

  try {
    localStorage.setItem('score', exercise.good + '/' + exercise.asked)
  } catch (e) {}
}

function updateProgress() {
  const target = exercise.reviewLength || exercise.length
  els.progress.classList.toggle('hidden', target === 0)
  if (target === 0) return

  els.progressText.textContent = (exercise.reviewLength ? 'Révision ' : 'Question ') + (exercise.asked + 1) + ' / ' + target
  els.progressFill.style.width = (exercise.asked / target) * 100 + '%'
}

function showRecap() {
  const total = exercise.asked
  const ratio = total > 0 ? Math.round((exercise.good / total) * 100) : 0
  const misses = exercise.misses

  els.play.classList.add('hidden')
  els.progress.classList.add('hidden')
  els.recap.classList.remove('hidden')
  els.recap.innerHTML = ''

  const title = document.createElement('h3')
  title.textContent = ratio === 100 ? 'Sans faute !' : ratio >= 80 ? 'Très bien !' : ratio >= 50 ? 'Pas mal !' : 'Continue, ça vient !'

  const score = document.createElement('p')
  score.className = 'recap-score'
  score.innerHTML = '<strong></strong> bonnes réponses sur <strong></strong> (<strong></strong>)'
  const [g, t, r] = score.querySelectorAll('strong')
  g.textContent = exercise.good
  t.textContent = total
  r.textContent = ratio + ' %'

  els.recap.append(title, score)

  if (misses.length > 0) {
    const caption = document.createElement('p')
    caption.textContent = 'À revoir :'
    const list = document.createElement('ul')
    list.className = 'recap-misses'

    misses.forEach((q) => {
      const li = document.createElement('li')
      const char = document.createElement('span')
      char.className = 'recap-char'
      char.lang = 'ja'
      char.textContent = q.char
      const right = document.createElement('span')
      right.lang = q.facet === 'read' ? 'ja' : 'en'
      right.textContent = alternatives(q.answer).join(' / ')
      li.append(char, right)
      list.appendChild(li)
    })

    els.recap.append(caption, list)
  }

  const actions = document.createElement('div')
  actions.className = 'recap-actions'

  if (misses.length > 0) {
    const review = document.createElement('button')
    review.type = 'button'
    review.className = 'recap-btn primary'
    review.textContent = 'Revoir mes erreurs (' + misses.length + ')'
    review.addEventListener('click', startReview)
    actions.appendChild(review)
  }

  const again = document.createElement('button')
  again.type = 'button'
  again.className = 'recap-btn'
  again.textContent = 'Nouvelle session'
  again.addEventListener('click', startGame)
  actions.appendChild(again)

  els.recap.appendChild(actions)
  actions.querySelector('button').focus()
}

// ---- Settings ----------------------------------------------------------------

function selectSetting(name, value) {
  if (name === 'mode') exercise.mode = value
  if (name === 'sessionLength') exercise.length = Number(value)

  try {
    localStorage.setItem(name, value)
  } catch (e) {}

  startGame()
}

document.querySelectorAll('input[name="mode"], input[name="sessionLength"]').forEach((radio) => {
  const current = radio.name === 'mode' ? exercise.mode : String(exercise.length)
  radio.checked = radio.value === current
  radio.addEventListener('change', () => selectSetting(radio.name, radio.value))
})

// 1-4 pick an option in the multiple-choice modes
document.addEventListener('keydown', (event) => {
  if (event.target.type === 'text' || event.ctrlKey || event.metaKey || event.altKey) return
  if (els.choices.classList.contains('hidden') || !els.recap.classList.contains('hidden')) return

  const button = els.choices.querySelectorAll('.choice')[Number(event.key) - 1]
  if (button) {
    event.preventDefault()
    button.click()
  }
})
