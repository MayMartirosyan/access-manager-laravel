import React, { useEffect, useState } from 'react';
import AppLayout from '../../Layouts/AppLayout';
import api from '@/Api/Api';


export default function RolesIndex() {
    const [items, setItems] = useState([]);
    const [error, setError] = useState(null);

    async function load() {
        try {
            const response = await api.get('/roles');
            setItems(response.data.data || []);
            setError(null);
        } catch (error) {
            console.error('Ошибка загрузки ролей:', error);
            setError(error.message || 'Не удалось загрузить роли');
        }
    }

    useEffect(() => {
        load();
    }, []);

    return (
        <AppLayout title="Роли">
            <div className="bg-white p-4 rounded shadow">
                {error && (
                    <div className="bg-red-100 text-red-700 p-3 rounded-md mb-4">
                        {error}
                    </div>
                )}
                <table className="w-full text-sm">
                    <thead>
                        <tr>
                            <th className="text-left">Название</th>
                            <th>Слаг</th>
                            <th>Дневные кредиты</th>
                        </tr>
                    </thead>
                    <tbody>
                        {items.map((r) => (
                            <tr key={r.id} className="border-t">
                                <td>{r.name}</td>
                                <td className="text-gray-500">{r.slug}</td>
                                <td className="text-center">{r.daily_credits}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </AppLayout>
    );
}