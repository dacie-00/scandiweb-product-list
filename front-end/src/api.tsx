import axios from "axios";
import {data} from "autoprefixer";
import { Product } from "./models/product";

export async function fetchProducts() {
    return await axios.get(
        'http://localhost:8000/products'
    )
        .then((response) => response.data);
}

export async function deleteProducts(products: Product[]) {
    const productIds = products.map(product => product.id);
    return await axios.delete(
        'http://localhost:8000/products',
        {data: {products: productIds}}
        );
}
