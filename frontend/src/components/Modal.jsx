import React from 'react';
import Button from './Button';
import './Modal.css';

const Modal = ({ isOpen, text, onConfirm, onCancel, confirmText = "Ya", cancelText = "Tidak" }) => {
  if (!isOpen) return null;

  return (
    <div className="modal-overlay-custom">
      <div className="modal-content-custom">
        <p className="modal-text-custom">{text}</p>
        <div className="modal-actions-custom">
          <Button variant="secondary" onClick={onCancel}>{cancelText}</Button>
          <Button variant="primary" onClick={onConfirm}>{confirmText}</Button>
        </div>
      </div>
    </div>
  );
};

export default Modal;