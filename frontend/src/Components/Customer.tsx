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
  document.title = document.title + " | Customer";

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
    <div className="orders-container">
      <h1>Customers</h1>
      {loading ? (
        <p>Loading...</p>
      ) : (
        <div>
          <table className="orders-table">
            <thead>
              <tr>
                <th>Id user</th>
                <th>Title</th>
                <th>Lastname</th>
                <th>Firstname</th>
                <th>Postal Code</th>
                <th>City</th>
                <th>Email</th>
                <th>Orders</th>
              </tr>
            </thead>
            <tbody>
              {customerData.map((customer) => (
                <tr>
                  <td>{`${customer.id || ''}`}</td>
                  <td>{`${customer.title === 1 ? 'M.' : 'Mme.'} ${customer.lastname} ${customer.firstname}`}</td>
                  <td>{`${customer.lastname || ''}`}</td>
                  <td>{`${customer.firstname || ''}`}</td>
                  <td>{`${customer.postal_code || ''}`}</td>
                  <td>{`${customer.city || ''}`}</td>
                  <td>{`${customer.email || ''}`}</td>
                  <td>{customer.orders > 0 ? (
                    <Link to={`/orders/${customer.id}`}>Show ({customer.orders})</Link>
                  ) : ('No order')
                  }
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
};

export default Customer;
