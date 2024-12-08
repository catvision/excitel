import React from 'react';
import { useStats } from './StatsContext';
// TableRow Component
const TableRow = ({ item }) => {
    let mrp = parseFloat((item.price*0.18).toFixed(2));
let woMRP = parseFloat((item.price-mrp).toFixed(2));
  return (
    <tr>
      <td>{item._id}</td>
      <td>{item.guid}</td>
      <td>{item.name}</td>
      <td>{item.plan_status}</td>
      <td>{item.price}</td>
      <td>{mrp}</td>
      <td>{woMRP}</td>
      <td>{item.plan_type}</td>
      <td>{item.category}</td>
      <td>{item.tags}</td>
    </tr>
  );
};

// Table Component
const Grid = () => {
    const { dbPlans } = useStats()??[];    
  return (
    <table border="1" cellPadding="10" cellSpacing="0" style={{ width: '100%', textAlign: 'left' }}>
      <thead>
        <tr>
          <th>ID</th>
          <th>GUID</th>
          <th>Name</th>
          <th>Status</th>
          <th>Price</th>
          <th>MRP</th>
          <th>W/O MRP</th>
          <th>Type</th>
          <th>Category</th>
          <th>Tags</th>
        </tr>
      </thead>
      <tbody>
        {dbPlans.map((item) => (
          <TableRow key={item._id} item={item} />
        ))}
      </tbody>
    </table>
  );
};

export default Grid;

