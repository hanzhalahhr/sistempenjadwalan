import React from 'react';
import './Table.css';

const Table = ({
  headers = [],
  children,
  className = ''
}) => {
  return (
    <div className={`table-wrapper ${className}`}>
      <table className="custom-table">
        <thead>
          <tr>
            {headers.map((head, idx) => (
              <th
                key={idx}
                style={{
                  textAlign: head === 'Aksi' ? 'center' : 'left'
                }}
              >
                {head}
              </th>
            ))}
          </tr>
        </thead>

        <tbody>
          {children}
        </tbody>
      </table>
    </div>
  );
};

export default Table;