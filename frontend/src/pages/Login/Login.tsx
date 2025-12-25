import React, { useEffect, useState } from "react";
import { Card } from "primereact/card";
import { InputText } from "primereact/inputtext";
import { Password } from "primereact/password";
import { Button } from "primereact/button";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../../context/AuthContext";
import "./Login.css";

const Login: React.FC = () => {
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();
  const { user, login } = useAuth();

  // Si ya hay sesión, no te quedes en /login (evita el bucle)
  useEffect(() => {
    if (user) {
      navigate("/catalog", { replace: true });
    }
  }, [user, navigate]);

  const handleLogin = () => {
    setError("");
    const success = login(username, password);
    if (success) {
      // Tu router usa /catalog, no /dashboard/catalog
      navigate("/catalog", { replace: true });
    } else {
      setError("Usuario o contraseña incorrectos");
    }
  };

  return (
    <div className="login-container">
      <Card className="login-card">
        <h2 className="login-title">Acceso al Sistema</h2>
        <div className="login-form">
          <div className="p-field">
            <label htmlFor="username">Usuario</label>
            <InputText
              id="username"
              value={username}
              onChange={(e) => setUsername(e.target.value)}
              placeholder="Ingrese su usuario"
            />
          </div>

          <div className="p-field">
            <label htmlFor="password">Contraseña</label>
            <Password
              id="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              feedback={false}
              toggleMask
              placeholder="Ingrese su contraseña"
            />
          </div>

          {error && <small className="error-text">{error}</small>}

          <Button
            label="Entrar"
            icon="pi pi-sign-in"
            className="login-button"
            onClick={handleLogin}
          />
        </div>
      </Card>
    </div>
  );
};

export default Login;