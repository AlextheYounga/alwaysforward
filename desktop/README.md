# Always Forward Desktop

This is a simple Electron app that contains no real AlwaysForward code, but simply opens a Chromium window and points to http://localhost:8124. 

```js
const createWindow = () => {
    // Create the browser window.
    const mainWindow = new BrowserWindow({
        icon: '/icons/icon.png',
        width: 800,
        height: 600,
    })

    // Load the AlwaysForward localhost port
    mainWindow.loadURL('http://127.0.0.1:8124')
}
```

Once (of if) Always Forward is live as a website, I can use this same strategy to load my website URL. In this way, I can do all my coding in Laravel, and access it from my desktop as an Electron wrapper. 

## Supervisor for Running Locally
I keep the app running on my machine using [supervisor](http://supervisord.org/), which after much struggle, I finally realized was the least dumb way to merge Electron and Laravel. Basically, there is no clean way to merge the two. I even tried storing php binaries in the Electron app and using them to start a php server on app startup, and that was really stupid. It also never worked. 

Supervisor is a fairly common approach to running tasks in the background, and is even recommended by Laravel for running background jobs. 

Please see `scripts/supervisor.sh` for more information about how to set this up.
