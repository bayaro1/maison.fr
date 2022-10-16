
// import('./functions.js').then((module) => {
//     module.clock(document.getElementById('hour'));
// });

import { CategoryAutoComplete } from './CategoryAutoComplete.js';
import * as F from './functions.js';

new CategoryAutoComplete(document.querySelector('#home-form input#category'));






// (async () => {
//     (await import('./functions.js')).clock(document.getElementById('hour'));
// })();


const goWork = new Date('October 25, 2022 08:30:00');
F.viewTimeCountBeforeDate(goWork, document.getElementById('hour'));

// F.altern(document.querySelector('#hour'));




// /**
//  * 
//  * @param {{title: string, body: string}} post 
//  * @returns 
//  */
// function createPost(post) {
//     let postDiv = document.createElement('div');
//     postDiv.innerHTML = '<h4>'+post.title+'</h4><p>'+post.body+'</p>';
//     return postDiv;
// }


// const loading = document.createElement('div');
// loading.innerHTML = '<strong>En cours de chargement...</strong>';

// const postsDiv = document.getElementById('posts');
// postsDiv.append(loading);
// try {
//     const res = await fetch('https://jsonplaceholder.typicode.com/posts?_delay=7000&_limit=20', {
//         headers: {
//             "Accept": "application/json"
//         }
//     });

//     if(!res.ok) {
//         throw new Error('erreur du serveur');
//     }
//     else {
//         const posts = await res.json();
//         loading.remove();
//         for(let post of posts) {
//             postsDiv.append(createPost(post));
//         }
//     }
// }
// catch(e) {
//     loading.innerHTML = '<strong>Echec du téléchargement</strong>';
// }



