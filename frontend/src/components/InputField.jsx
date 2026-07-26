import React, { useState } from 'react';
import { Eye, EyeSlash } from '@phosphor-icons/react'; // Menggunakan Phosphor
import './InputField.css';

const InputField = ({ 
  label, 
  type = 'text', 
  options = [], 
  className = '', 
  leftIcon,
  ...props 
}) => {
  const isSelect = type === 'select';
  const isPassword = type === 'password';
  
  const [showPassword, setShowPassword] = useState(false);

  const togglePassword = () => setShowPassword(!showPassword);
  
  const currentType = isPassword ? (showPassword ? 'text' : 'password') : type;

  return (
    <div className={`form-input-group ${className}`}>
      {label && <label>{label}</label>}
      
      <div className="input-wrapper">
        {leftIcon && (
          <div className="input-left-icon">
            {leftIcon}
          </div>
        )}

        {isSelect ? (
          <select className={`input-field select-custom ${leftIcon ? 'has-left-icon' : ''}`} {...props}>
            {options.map((opt, idx) => (
              <option key={idx} value={opt.value || opt}>{opt.label || opt}</option>
            ))}
          </select>
        ) : (
          <input 
            type={currentType} 
            className={`input-field ${leftIcon ? 'has-left-icon' : ''}`} 
            {...props} 
          />
        )}

        {isPassword && (
          <div className="input-right-icon password-toggle" onClick={togglePassword}>
            {showPassword ? <Eye size={20} weight="bold" /> : <EyeSlash size={20} weight="bold" />}
          </div>
        )}
      </div>
    </div>
  );
};

export default InputField;