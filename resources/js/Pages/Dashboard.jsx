import React from 'react';
import AppLayout from '../Layouts/AppLayout';
import { Link } from '@inertiajs/react';

export default function Dashboard({ user }) {

    const userData = user.data
    return (
        <AppLayout title="Дашборд">
            <div className="bg-white p-4 rounded shadow">
                <h2 className="text-xl font-bold mb-4">Добро пожаловать, {userData.name}!</h2>
                <p>Email: {userData.email}</p>
                <p>Роли: {(userData.roles || []).map((r) => r.name).join(', ')}</p>
                <p>Остаток кредитов: {userData.credits_remaining}</p>
                <div className="mt-4">
                    <h3 className="text-lg font-semibold">Навигация</h3>
                    <ul className="list-disc pl-5">
                        <li>
                            <Link href="/content" className="text-blue-600 hover:underline">
                                Просмотр контента
                            </Link>
                        </li>
                        {userData.is_admin && (
                            <>
                                <li>
                                    <Link href="/users" className="text-blue-600 hover:underline">
                                        Управление пользователями
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/roles" className="text-blue-600 hover:underline">
                                        Управление ролями
                                    </Link>
                                </li>
                            </>
                        )}
                    </ul>
                </div>
            </div>
        </AppLayout>
    );
}