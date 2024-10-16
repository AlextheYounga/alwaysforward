// main.js

// Modules to control application life and create native browser window
const fs = require('fs');
const path = require('path')
const { exec } = require('child_process');
const { app, BrowserWindow, nativeImage } = require('electron')

const electronResources = process.resourcesPath;
const serverUrl = fs.readFileSync(path.join(electronResources, 'build/app_url'), 'utf8')

if (!process.env.PHP_RUNNING && serverUrl.includes('localhost')) {
	// Set up for local server
	const php = path.join(electronResources, 'build/php/php');
	const laravel = path.join(electronResources, 'build/laravel');
	
	// Start php server
	exec(`${php} -S localhost:8124 -t ${laravel}/public`);
	process.env.PHP_RUNNING = true;
}

const createWindow = () => {
    // Create the browser window.
    const mainWindow = new BrowserWindow({
        icon: '/icons/icon.png',
        width: 800,
        height: 600,
    })

    mainWindow.loadURL(serverUrl).catch(() => {
        mainWindow.loadFile('pages/error.html')
    })

    // Open the DevTools.
    // mainWindow.webContents.openDevTools()
}

// This method will be called when Electron has finished
// initialization and is ready to create browser windows.
// Some APIs can only be used after this event occurs.
app.whenReady().then(() => {
    createWindow()

    app.on('activate', () => {
        // On macOS it's common to re-create a window in the app when the
        // dock icon is clicked and there are no other windows open.
        if (BrowserWindow.getAllWindows().length === 0) createWindow()
    })
})

const image = nativeImage.createFromPath(
    path.join(__dirname, "icons/icon.png")
);

// set dock icon for macos
if (process.platform === 'darwin') {
    app.dock.setIcon(image);
}

// Quit when all windows are closed, except on macOS. There, it's common
// for applications and their menu bar to stay active until the user quits
// explicitly with Cmd + Q.
app.on('window-all-closed', () => {
    if (process.platform !== 'darwin') app.quit()
})

// In this file you can include the rest of your app's specific main process
// code. You can also put them in separate files and require them here.