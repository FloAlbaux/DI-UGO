import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';

interface CustomerData {
  id: number;
  title: number;
  lastname: string;
  firstname: string;
  postal_code: number | null;
  city: string;
  email: string;
  orders: number;
}

const Customer: React.FC = () => {
  const [customerData, setCustomerData] = useState<CustomerData[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchCustomerData = async () => {
      try {
        const response = await fetch('http://localhost:8000/customers');
        if (!response.ok) {
          throw new Error('Failed to fetch customer data');
        }

        const data: CustomerData[] = await response.json();
        setCustomerData(data);
        setLoading(false);
      } catch (error) {
        console.error(error);
      }
    };

    fetchCustomerData();
  }, []);

  return (
    <div>
      <h1>Customer Page</h1>
      {loading ? (
        <p>Loading...</p>
      ) : (
        <ul>
          {customerData.map((customer) => (
            <li key={customer.id}>
              <p>{`${customer.title === 1 ? 'M.' : 'Mme.'} ${customer.lastname} ${customer.firstname}`}</p>
              <p>{`Postal Code: ${customer.postal_code || 'N/A'}`}</p>
              <p>{`City: ${customer.city || 'N/A'}`}</p>
              <p>{`Email: ${customer.email || 'N/A'}`}</p>
              <Link to={`/orders/${customer.id}`}>Show ({customer.orders})</Link>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
};

export default Customer;
