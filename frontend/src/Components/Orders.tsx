import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { Link } from 'react-router-dom';

interface RouteParams {
    [key: string]: string | undefined;
    orderId?: string;
}

const Orders: React.FC = () => {
    const [customerData, setCustomerData] = useState<any>(null);
    const { orderId } = useParams<RouteParams>();

    useEffect(() => {
        if (orderId) {
            fetch(`http://localhost:8000/customers/${orderId}/orders`)
                .then(response => response.json())
                .then(data => setCustomerData(data))
                .catch(error => console.error('Error fetching customer orders:', error));
        }
    }, []);

    return (
        <div>
            {customerData && (
                <div>
                    <h1>Orders of {customerData.last_name}</h1>
                    <table>
                        <thead>
                            <tr>
                                <th>Purchase Identifier</th>
                                <th>Product ID</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Currency</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            {customerData.orders.map((order: any) => (
                                <tr key={order.purchase_identifier}>
                                    <td>{order.purchase_identifier || 'N/A'}</td>
                                    <td>{order.product_id || 'N/A'}</td>
                                    <td>{order.quantity || 'N/A'}</td>
                                    <td>{order.price || 'N/A'}</td>
                                    <td>{order.currency || 'N/A'}</td>
                                    <td>{order.date || 'N/A'}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            )}
            <Link to={`/`}>Go back</Link>
        </div>
    );
}

export default Orders;
