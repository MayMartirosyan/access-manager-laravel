import { Head, useForm } from "@inertiajs/react";

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
    });

    const handleRegister = (e) => {
        e.preventDefault();

        post(route('register.store'), {
            onFinish: () => reset('password', 'password_confirmation'),
            onSuccess: (response) => {
                if (response.props.token) {
                    localStorage.setItem('sanctum_token', response.props.token);
                    localStorage.setItem('user', JSON.stringify(response.props.user));
                    window.location.href = response.props.redirect;
                }
            },
            onError: (errors) => console.error('Ошибки регистрации:', errors),
        });
    };

    return (
        <div className="max-w-md mx-auto mt-24 bg-white shadow rounded p-6">
            <h1 className="text-xl font-semibold mb-4">Регистрация</h1>
            <form onSubmit={handleRegister} className="space-y-3">
                <input
                    className="w-full border p-2 rounded"
                    value={data.name}
                    onChange={(e) => setData("name", e.target.value)}
                    placeholder="Имя"
                />
                <input
                    className="w-full border p-2 rounded"
                    value={data.email}
                    onChange={(e) => setData("email", e.target.value)}
                    placeholder="Email"
                />
                <input
                    type="password"
                    className="w-full border p-2 rounded"
                    value={data.password}
                    onChange={(e) => setData("password", e.target.value)}
                    placeholder="Пароль"
                />
                <input
                    type="password"
                    className="w-full border p-2 rounded"
                    value={data.password_confirmation}
                    onChange={(e) =>
                        setData("password_confirmation", e.target.value)
                    }
                    placeholder="Подтвердите пароль"
                />
                <button className="bg-blue-600 text-white rounded px-4 py-2">
                    Зарегистрироваться
                </button>
            </form>
        </div>
    );
}