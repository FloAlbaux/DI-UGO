import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { Link } from 'react-router-dom';
import '../Style/table.css'

interface RouteParams {
    [key: string]: string | undefined;
    orderId?: string;
}

const Orders: React.FC = () => {
    document.title = document.title + " | Oders";

    const [customerData, setCustomerData] = useState<any>(null);
    const [loading, setLoading] = useState(true);
    const { orderId } = useParams<RouteParams>();

    useEffect(() => {
        if (orderId) {
            const apiUrl = process.env.REACT_APP_API_URL || 'http://localhost:8000/';
            fetch(apiUrl + `customers/${orderId}/orders`)
                .then(response => response.json())
                .then(data => {
                    setCustomerData(data)
                    setLoading(false)
                })
                .catch(error => console.error('Error fetching customer orders:', error));
        }
    }, [orderId]);

    return (
        <div className="orders-container">
            {loading ? (
                <div>
                    <h1>Orders</h1>
                    <p>Loading...</p>
                </div>
            ) : (
                <div>
                    <h1>Orders of {customerData.last_name}</h1>
                    <table className="orders-table">
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
                                <tr key={order.product_id}>
                                    <td>{order.purchase_identifier || ''}</td>
                                    <td>{order.product_id || ''}</td>
                                    <td>{order.quantity || ''}</td>
                                    <td>{order.price || ''}</td>
                                    <td>{order.currency || ''}</td>
                                    <td>{order.date || ''}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div >
            )}
            <Link to={`/`} className="go-back-link">&lt;- Go back</Link>
        </div >
    );
}

export default Orders;
