import { useEffect, useState, useRef } from 'react';
import { useSearchParams, useNavigate } from 'react-router-dom';
import axios from 'axios';

const AuthCallback = () => {
  const [searchParams] = useSearchParams();
  const [user, setUser] = useState(null);
  const [error, setError] = useState(null);
  const navigate = useNavigate();
  const initialized = useRef(false);

  useEffect(() => {
    if (initialized.current) return;
    
    const code = searchParams.get('code');
    console.log("AuthCallback: Nhận được mã code từ URL:", code);
    
    if (code) {
      initialized.current = true;
      axios.post('http://localhost:5001/api/auth/callback', { code })
        .then(res => {
          console.log("AuthCallback: Đã nhận dữ liệu từ Backend:", res.data);
          setUser(res.data);
        })
        .catch(err => {
          console.error("AuthCallback: Lỗi khi gọi API Backend:", err);
          setError(err.response?.data?.details || err.message);
        });
    } else {
      console.warn("AuthCallback: Không tìm thấy mã code trong URL");
      setError('Không tìm thấy mã code trong URL');
    }
  }, [searchParams]);

  if (error) {
    return (
      <div style={{ color: 'red', textAlign: 'center', marginTop: '50px' }}>
        <h2>Lỗi đăng nhập</h2>
        <pre style={{ background: '#f8d7da', padding: '10px' }}>{JSON.stringify(error, null, 2)}</pre>
        <button onClick={() => navigate('/')}>Quay lại</button>
      </div>
    );
  }

  if (!user) {
    return <div style={{ textAlign: 'center', marginTop: '100px' }}>Đang xác thực với Microsoft...</div>;
  }

  return (
    <div style={{ maxWidth: '600px', margin: '50px auto', padding: '20px', border: '1px solid #ccc', borderRadius: '8px' }}>
      <h1 style={{ color: 'green' }}>Đăng nhập thành công!</h1>
      <p>Chào mừng, <strong>{user.displayName}</strong>!</p>
      <p>Email: {user.mail || user.userPrincipalName}</p>
      
      <h3>Dữ liệu chi tiết từ Graph API:</h3>
      <pre style={{ background: '#eee', padding: '10px', borderRadius: '4px', overflowX: auto }}>
        {JSON.stringify(user, null, 2)}
      </pre>
      
      <button onClick={() => navigate('/')} style={{ marginTop: '20px' }}>Đăng xuất / Quay lại</button>
    </div>
  );
};

export default AuthCallback;
