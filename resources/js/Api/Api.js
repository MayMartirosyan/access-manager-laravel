import axios from "axios";

const api = axios.create({
    baseURL: "/api/v1",
});

api.interceptors.request.use((config) => {
    const token = localStorage.getItem("sanctum_token");

    if (!token) {
        throw new Error("Токен отсутствует. Пожалуйста, войдите снова.");
    }

    config.headers.Authorization = `Bearer ${token}`;
    config.headers.Accept = "application/json";
    return config;
});
api.interceptors.response.use(
    (response) => {
        if (response.data.user) {
            localStorage.setItem("user", JSON.stringify(response.data.user));
        }
        return response;
    },
    (error) => {
        if (
            error.response &&
            (error.response.status === 403 || error.response.status === 429) &&
            (error.response.data.message === "Недостаточно кредитов" ||
                error.response.data.message ===
                    "Daily API credit limit reached")
        ) {
            const user = JSON.parse(localStorage.getItem("user")) || {};
            localStorage.setItem(
                "user",
                JSON.stringify({ ...user, credits_remaining: 0 })
            );
            return Promise.reject(
                // new Error(
                    "Ваши кредиты закончились. Вы не можете выполнять API-запросы."
                // )
            );
        }
        return Promise.reject(error);
    }
);

export default api;
