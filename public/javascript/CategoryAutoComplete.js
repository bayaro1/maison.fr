import { myGetFetch } from "./functions.js";

export class CategoryAutoComplete {

    /** @type {HTMLInputElement} */
    #input;
    
    /**
     * 
     * @param {HTMLInputElement} input 
     */
    constructor(input) {
        input.addEventListener('input', this.#onInput);
        this.#input = input;
    }

    /**
     * 
     * @param {InputEvent} e 
     */
    async #onInput(e) {
        
        document.getElementById('under').style.display = 'none';
        const value = e.currentTarget.value;
        const suggest = document.getElementById('category_suggest');
        

        if(value === '') {
            suggest.innerHTML = '';
            document.getElementById('under').style.display = '';
            return;
        }

        suggest.addEventListener('mousechange', function(e) {
            for(const elt of suggest.children) {
                if(e.detail !== elt) {
                    elt.style.backgroundColor = '';
                    elt.style.color = 'grey';
                }
            }
        });

        try {
            document.getElementById('load').style.display = '';
            const categories = await myGetFetch('/category-search/'+value);
            suggest.innerHTML = '';
            
            document.getElementById('load').style.display = 'none';

            categories.forEach(function(category) {
                const categoryElt = document.createElement('p');
                categoryElt.innerHTML = category;
                categoryElt.style.margin = '0px';
                categoryElt.style.height = '40px';
                categoryElt.style.padding = '5%';
                categoryElt.style.verticalAlign = 'center';
                suggest.append(categoryElt);
                categoryElt.addEventListener('mouseover', function(e) {
                    this.style.backgroundColor = 'rgba(36, 129, 13, 0.842)';
                    this.style.color = 'white';
                    this.parentElement.dispatchEvent(new CustomEvent('mousechange', {
                        bubbles: true,
                        detail: this
                    }));

                });
                categoryElt.addEventListener('click', function(e) {
                    const input = document.querySelector('#home-form input#category');
                    input.value = e.currentTarget.innerText;
                    e.currentTarget.parentElement.innerHTML = '';
                    document.getElementById('under').style.display = '';
                });
            });
        } catch(e) {
            console.error(e);
        }
    }

    /**
     * 
     * @param {Event} e 
     */
    #onSuggestClick(e) {
        console.log(e.currentTarget);
        // this.#input.setAttribute('value', e.currentTarget.value);
    }
}