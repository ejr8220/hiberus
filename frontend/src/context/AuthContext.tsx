import React, { createContext, useState, useContext } from "react";

interface AuthContextType {
  user: string | null;
  login: (username: string, password: string) => boolean;
  logout: () => void;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  // Inicializa desde localStorage para evitar parpadeo y bucles
  const [user, setUser] = useState<string | null>(localStorage.getItem("role"));

  const login = (username: string, password: string) => {
    if (username === "Admin" && password === "Admin") {
      localStorage.setItem("role", "ADMIN");
      setUser("ADMIN");
      return true;
    }
    if (username === "Cliente" && password === "Cliente") {
      localStorage.setItem("role", "CLIENTE");
      setUser("CLIENTE");
      return true;
    }
    return false;
  };

  const logout = () => {
    localStorage.removeItem("role");
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ user, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error("useAuth debe usarse dentro de un AuthProvider");
  return ctx;
};