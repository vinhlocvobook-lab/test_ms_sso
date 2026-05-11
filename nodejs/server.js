const express = require('express');
const axios = require('axios');
const dotenv = require('dotenv');
const cors = require('cors');
const path = require('path');

// Load .env từ thư mục gốc
dotenv.config({ path: path.join(__dirname, '../.env') });

const app = express();
app.use(cors());
app.use(express.json());

const PORT = 5000;
const CLIENT_ID = process.env.MICROSOFT_CLIENT_ID;
const TENANT_ID = process.env.MICROSOFT_TENANT_ID || 'common';
const CLIENT_SECRET = process.env.MICROSOFT_CLIENT_SECRET;
const REDIRECT_URI = 'http://localhost:3000/auth/callback'; // Redirect về Frontend React

// Endpoint 1: Trả về URL đăng nhập Microsoft
app.get('/api/auth/url', (req, res) => {
    const authUrl = `https://login.microsoftonline.com/${TENANT_ID}/oauth2/v2.0/authorize?client_id=${CLIENT_ID}&response_type=code&redirect_uri=${encodeURIComponent(REDIRECT_URI)}&response_mode=query&scope=openid%20profile%20email%20User.Read`;
    res.json({ url: authUrl });
});

// Endpoint 2: Đổi mã code lấy Access Token và lấy thông tin User
app.post('/api/auth/callback', async (req, res) => {
    const { code } = req.body;

    if (!code) {
        return res.status(400).json({ error: 'Thiếu mã code' });
    }

    try {
        // 1. Đổi code lấy token
        const tokenResponse = await axios.post(
            `https://login.microsoftonline.com/${TENANT_ID}/oauth2/v2.0/token`,
            new URLSearchParams({
                client_id: CLIENT_ID,
                scope: 'openid profile email User.Read',
                code: code,
                redirect_uri: REDIRECT_URI,
                grant_type: 'authorization_code',
                client_secret: CLIENT_SECRET,
            }).toString(),
            { headers: { 'Content-Type': 'application/x-www-form-urlencoded' } }
        );

        const accessToken = tokenResponse.data.access_token;

        // 2. Gọi Graph API lấy info user
        const userResponse = await axios.get('https://graph.microsoft.com/v1.0/me', {
            headers: { Authorization: `Bearer ${accessToken}` }
        });

        res.json(userResponse.data);
    } catch (error) {
        console.error('Lỗi SSO:', error.response ? error.response.data : error.message);
        res.status(500).json({ error: 'Đăng nhập thất bại', details: error.response ? error.response.data : error.message });
    }
});

app.listen(PORT, () => {
    console.log(`Server NodeJS đang chạy tại http://localhost:${PORT}`);
});
