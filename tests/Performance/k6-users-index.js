import http from "k6/http";
import { sleep, check } from "k6";

export const options = {
    vus: 20,
    duration: "30s",
};

const BASE = __ENV.BASE_URL || "http://localhost:8000";
const TOKEN = __ENV.TOKEN;

export default function () {
    const res = http.get(`${BASE}/api/v1/users`, {
        headers: { Authorization: `Bearer ${TOKEN}`, Accept: "application/json" },
    });
    check(res, { "status is 200": (r) => r.status === 200 });
    sleep(0.1);
}