# Changelog
## v1.0.3
### Added or Changed
- delete all traces related to 'PictureTagDAO' Add a function to replace deleted features
- Edit RoadMaps
- Edit Readme
- 500 Ko Maximum for a picture upload

### Removed
- Picture Tag DAO (Multipletag by picture) cause of Some bugs

### Bugfix
- Picture tag not initialised
- Category appears if more than 5 photos with the same tag and remove if number of picture less than 5
- upload picture size (NOW 500 Ko MAXIMUM, Previous 50 Ko)
- Fix Faild upload images Error -> upload in db is true
- Fix tags atribute => stop use table PicturesTags
- Fix email administrator


## v1.0.2
- ONLY LOCAL VERSION


## v1.0.1
### Added or Changed
- Added this changelog :)
- Finished ReadMe

### Bugfix
- Fixed use of the 'PictureTag' table, and correction of the assignment of a tag at the upload of an image
- Fixed Image upload with error => now if an error occurred the image will not be sent to DB
- Fixed admin Email not Uploaded

### Removed
- Back to top link in readme


## v1.0.0
### Added
- Upload Project on OVH
- Make project public on GitHub