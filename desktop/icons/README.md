For MacOs, getting the image size right is a massive pain in the ass.
Saving a note here for myself later.

I use [electron-icon-maker]([electron-icon-maker](https://www.npmjs.com/package/electron-icon-maker)) to create the icon images. It will generate mac and windows icons.

But MacOs desktop icons are misleading. I estimate they need whitespace of 1024 + 17% in order to get the size right in the dock. So, in Gimp, I resized the canvas to 1198px (next time try 1200px even), and then resized the image to 1024px. This gave me the desired dock icon size. 