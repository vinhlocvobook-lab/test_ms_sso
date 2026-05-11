import axios from 'axios';

const Login = () => {
  const handleLogin = async () => {
    try {
      // Gọi backend để lấy URL đăng nhập Microsoft
      const response = await axios.get('http://localhost:5001/api/auth/url');
      window.location.href = response.data.url;
    } catch (error) {
      console.error('Lỗi khi lấy URL đăng nhập:', error);
      alert('Không thể kết nối với Backend NodeJS (Port 5001)');
    }
  };

  return (
    <div className="container" style={{ textAlign: 'center', marginTop: '100px' }}>
      <h1>Microsoft SSO - ReactJS</h1>
      <p>Thử nghiệm đăng nhập SSO qua Backend NodeJS</p>
      <button 
        onClick={handleLogin} 
        style={{
          backgroundColor: '#2f2f2f',
          color: 'white',
          padding: '12px 24px',
          border: 'none',
          borderRadius: '4px',
          fontSize: '16px',
          fontWeight: '600',
          cursor: 'pointer'
        }}
      >
        Đăng nhập với Microsoft
      </button>
    </div>
  );
};

export default Login;
