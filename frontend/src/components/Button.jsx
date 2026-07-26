import React from 'react';
import './Button.css';

const Button = ({ 
  children, 
  variant = 'primary',
  icon: Icon,
  className = '', 
  disabled = false,
  ...props 
}) => {
  return (
    <button 
      className={`btn-custom btn-${variant} ${className}`} 
      disabled={disabled}
      {...props}
    >
      {Icon && <Icon className="btn-icon-spacing" size={20} weight="bold" />}
      {children}
    </button>
  );
};

export default Button;