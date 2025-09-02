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

        post(route("register.store"), {
            onFinish: () => reset("password", "password_confirmation"),
            onSuccess: (response) => {
                if (response.props.token) {
                    localStorage.setItem("sanctum_token", response.props.token);
                    localStorage.setItem(
                        "user",
                        JSON.stringify(response.props.user)
                    );
                    window.location.href = response.props.redirect;
                }
            },
            onError: (errors) => console.error("Ошибки регистрации:", errors),
        });
    };

    return (
        <main className="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 p-4">
            <section className="w-full max-w-md bg-white rounded-xl shadow-2xl p-8 transform transition-all duration-300 hover:shadow-xl">
                <h1 className="text-3xl font-bold text-gray-900 mb-6 text-center">
                    Создать аккаунт
                </h1>

                {(errors.name || errors.email || errors.password) && (
                    <div className="bg-red-100 text-red-700 p-3 rounded-md mb-6 text-sm text-center">
                        {errors.name && <div>{errors.name}</div>}
                        {errors.email && <div>{errors.email}</div>}
                        {errors.password && <div>{errors.password}</div>}
                    </div>
                )}

                <form onSubmit={handleRegister} className="space-y-6">
                    <div>
                        <label
                            htmlFor="name"
                            className="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Имя
                        </label>
                        <input
                            id="name"
                            type="text"
                            required
                            className="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                            value={data.name}
                            onChange={(e) => setData("name", e.target.value)}
                            placeholder="Ваше имя"
                        />
                    </div>
                    <div>
                        <label
                            htmlFor="email"
                            className="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Email
                        </label>
                        <input
                            id="email"
                            type="email"
                            required
                            className="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                            value={data.email}
                            onChange={(e) => setData("email", e.target.value)}
                            placeholder="Введите ваш email"
                        />
                    </div>
                    <div>
                        <label
                            htmlFor="password"
                            className="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Пароль
                        </label>
                        <input
                            id="password"
                            type="password"
                            required
                            className="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                            value={data.password}
                            onChange={(e) =>
                                setData("password", e.target.value)
                            }
                            placeholder="********"
                        />
                    </div>
                    <div>
                        <label
                            htmlFor="password_confirmation"
                            className="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Подтверждение пароля
                        </label>
                        <input
                            id="password_confirmation"
                            type="password"
                            required
                            className="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                            value={data.password_confirmation}
                            onChange={(e) =>
                                setData("password_confirmation", e.target.value)
                            }
                            placeholder="********"
                        />
                    </div>
                    <button
                        type="submit"
                        disabled={processing}
                        className="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200 disabled:opacity-50"
                    >
                        {processing ? "Регистрация..." : "Зарегистрироваться"}
                    </button>
                </form>

                <div className="mt-6 text-center">
                    <p className="text-sm text-gray-500">
                        Уже есть аккаунт?{" "}
                        <a
                            href="/login"
                            className="text-blue-600 hover:underline font-medium"
                        >
                            Войти
                        </a>
                    </p>
                </div>
            </section>
        </main>
    );
}
