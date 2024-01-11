// Standalone example to demonstrate codeflow.
// Start the server, hit localhost:3000 on the browser, and click through.
// On the server logs, you should have the auth code, as well as the token
// from exchanging it. This exchange is invisible to the app user

// const fetch = require('node-fetch');
// const app = require('express')();

import fetch from 'node-fetch';
import express from 'express';
import dotenv from 'dotenv';
import { Dropbox } from 'dropbox'; // eslint-disable-line import/no-unresolved
import fs from 'fs'
import open from 'open'

// If we already have a refresh token, we don't need to do anything.
if (fs.existsSync('storage/app/public/.refresh_token')) {
    process.exit();
}

dotenv.config('../.env');

const app = express();
const hostname = 'localhost';
const port = 3002;

const config = {
    fetch,
    clientId: process.env.DROPBOX_APP_KEY,
    clientSecret: process.env.DROPBOX_APP_SECRET,
};

const dbx = new Dropbox(config);

console.log('Please open your browser window if not opened automatically...');
open(`http://${hostname}:${port}`);

const redirectUri = `http://${hostname}:${port}/auth`;
app.get('/', (_, res) => {
    dbx.auth.getAuthenticationUrl(redirectUri, null, 'code', 'offline', null, 'none', false)
        .then((authUrl) => {
            res.writeHead(302, { Location: authUrl });
            res.end();
        });
});

app.get('/auth', (req, res) => { // eslint-disable-line no-unused-vars
    const { code } = req.query;
    console.log(`code:${code}`);

    dbx.auth.getAccessTokenFromCode(redirectUri, code)
        .then((token) => {
            console.log('Successfully authenticated!');

            token.expires_at = Date.now() + token.result.expires_in * 1000;
            const refreshToken = token.result.refresh_token;

            fs.writeFileSync('storage/app/public/token.json', JSON.stringify(token));
            fs.writeFileSync('storage/app/public/.refresh_token', refreshToken);

            console.log('Feel free to close browser window.');

            process.exit();
        })
        .catch((error) => {
            console.error(error);
        });
    res.end();
});

app.listen(port);