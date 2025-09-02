import React from 'react';
import { Link, usePage, useForm } from '@inertiajs/react';

export default function AppLayout({ children, title = 'AccessManager' }) {
    const { props } = usePage();
    const { post, processing } = useForm();

    const user = JSON.parse(localStorage.getItem('user')) || {};
    const isAdmin = user.is_admin || false;

    const handleLogout = () => {
        post(route('logout'), {
            onSuccess: () => {
                localStorage.removeItem('sanctum_token');
                localStorage.removeItem('user');
                window.location.href = '/login';
            },
            onError: (errors) => {
                console.error('Logout errors:', errors);
            },
        });
    };

    return (
        <div className="min-h-screen bg-gray-50 text-gray-900">
            <header className="bg-white shadow">
                <div className="max-w-5xl mx-auto p-4 flex gap-4 items-center">
                    <Link href="/dashboard" className="font-semibold">
                        Dashboard
                    </Link>
                    {isAdmin && (
                        <>
                            <Link href="/users">Users</Link>
                            <Link href="/roles">Roles</Link>
                        </>
                    )}
                    <div className="ml-auto flex items-center gap-4">
                        {props.auth.user && (
                            <button
                                onClick={handleLogout}
                                disabled={processing}
                                className="bg-red-500 text-white px-4 py-1 rounded-md hover:bg-red-600 disabled:bg-red-300"
                            >
                                Выйти
                            </button>
                        )}
                    </div>
                </div>
            </header>
            <main className="max-w-5xl mx-auto p-6">{children}</main>
        </div>
    );
}