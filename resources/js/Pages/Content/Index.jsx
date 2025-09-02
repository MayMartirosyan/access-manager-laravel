import React, { useEffect, useState } from "react";
import AppLayout from "../../Layouts/AppLayout";
import api from "@/Api/Api";

export default function ContentIndex() {
    const [items, setItems] = useState([]);
    const [error, setError] = useState(null);

    async function load() {
        try {
            const response = await api.get("/content");

            console.log(response,'asdasd');
            setItems(response.data.data || []);
            setError(null);
        } catch (error) {
            console.log(typeof error,'error');
            setError(
                error.response?.data?.message || error
            );
        }
    }

    useEffect(() => {
        load();
    }, []);

    return (
        <AppLayout title="Контент">
            <div className="bg-white p-4 rounded shadow">
                {error && (
                    <div className="bg-red-100 text-red-700 p-3 rounded-md mb-4">
                        {error}
                    </div>
                )}
                <h2 className="text-xl font-bold mb-4">Контент</h2>
                <table className="w-full text-sm">
                    <thead>
                        <tr>
                            <th className="text-left">Дата</th>
                            <th className="text-left">Текст</th>
                        </tr>
                    </thead>
                    <tbody>
                        {items.map((item) => (
                            <tr key={item.id} className="border-t">
                                <td>{item.display_date}</td>
                                <td>{item.text}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </AppLayout>
    );
}
