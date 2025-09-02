import { createInertiaApp } from "@inertiajs/react";
import { createRoot } from "react-dom/client";

import "../css/app.css";
// import "./bootstrap";

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.jsx", { eager: true });
        return pages[`./Pages/${name}.jsx`];
    },
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },
});


// import { createInertiaApp } from '@inertiajs/react';
// import { createRoot } from 'react-dom/client';


// import "../css/app.css";

// const initApp = async () => {
 
//     await fetch('/sanctum/csrf-cookie', {
//         method: 'GET',
//         credentials: 'include',
//     });

//     createInertiaApp({
//       resolve: (name) => {
//           const pages = import.meta.glob("./Pages/**/*.jsx", { eager: true });
//           return pages[`./Pages/${name}.jsx`];
//       },
//       setup({ el, App, props }) {
//           createRoot(el).render(<App {...props} />);
//       },
//   });
// };

// initApp();