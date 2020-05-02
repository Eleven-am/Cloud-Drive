# Cloud-Drive
A personal cloud server built with CSS, PHP, JQuery and vanilla Javascript

This project was somthing i built to pass the time during the quarantine. This is a welcome alternative to the default apache file system interface.

This website has some basic security features.
* Instead of having a url to the absolute path of file of folder shown, it is replaced with a randomnly generated 13 character string. This therefore means your items can not be easily located for download or maybe modification 
* Certain sensitive actions require authentication to continue, for example you need to autheticate to delete, move or rename files or folders.
* A scope can be set. This scope is a directory and everything in this folder, ie. subfolders and files, in accordance with item 2 above, are made available to everyone that has your domain name but anything outside this scope also requires authentication

The website is fully dynamic and this brings with some extra added features.
* You can initiate an upload (by either drag and drop or by clicking on the upload button) and still be able to navigate through other folders or view files without affecting the upload
