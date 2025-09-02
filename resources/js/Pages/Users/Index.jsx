import React, { useEffect, useState } from 'react';
import AppLayout from '../../Layouts/AppLayout';
import api from '@/Api/Api';


export default function UsersIndex() {
    const [items, setItems] = useState([]);
    const [search, setSearch] = useState('');
    const [error, setError] = useState(null);

    async function load() {
        try {
            const response = await api.get('/users' + (search ? `?search=${encodeURIComponent(search)}` : ''));
            setItems(response.data.data || []);
            setError(null);
        } catch (error) {
            setError(error.message || 'Не удалось загрузить пользователей');
        }
    }

    useEffect(() => {
        load();
    }, [search]);

    return (
        <AppLayout title="Пользователи">
            <div className="bg-white p-4 rounded shadow">
                {error && (
                    <div className="bg-red-100 text-red-700 p-3 rounded-md mb-4">
                        {error}
                    </div>
                )}
                <div className="flex gap-2 mb-3">
                    <input
                        className="border rounded p-2 flex-1"
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                        placeholder="Поиск по имени или email"
                    />
                    <button
                        onClick={load}
                        className="bg-blue-600 text-white rounded px-4"
                    >
                        Поиск
                    </button>
                </div>
                <table className="w-full text-sm">
                    <thead>
                        <tr>
                            <th className="text-left">Имя</th>
                            <th className="text-left">Email</th>
                            <th>Роль</th>
                        </tr>
                    </thead>
                    <tbody>
                        {items.map((u) => (
                            <tr key={u.id} className="border-t">
                                <td>{u.name}</td>
                                <td>{u.email}</td>
                                <td>{(u.roles || []).map((r) => r.name).join(', ')}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </AppLayout>
    );
}