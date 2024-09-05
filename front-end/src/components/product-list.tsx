import React from "react";
import {Product} from "@/models/product";
import {ProductCard} from "@/components/product-card";
import {Button} from "@/components/ui/button";
import {Link} from "@tanstack/react-router";

type ProductListProps = {
    products: Product[]
    onDelete: (products: Product[]) => void
}
export function ProductList({products, onDelete}: ProductListProps) {
    const handleDelete = () => {
        const toDelete = products.filter((product) => product.toDelete);
        onDelete(toDelete);
    }

    return (
        <div>
            <header className="flex items-center justify-between mb-4">
                <h1 className="text-2xl font-bold">Product List</h1>
                <div className="space-x-4">
                    <Link to="/addproduct">
                        <Button>ADD</Button>
                    </Link>
                    <Button id="delete-product-btn" onClick={handleDelete}>MASS DELETE</Button>
                </div>
            </header>
            <hr className="h-px my-8 bg-slate-300 border-0 dark:bg-gray-700"></hr>
            <main className="grid grid-cols-4 gap-4">
                {products.map((product) => (
                    <ProductCard key={product.id} product={product}/>
                ))}
            </main>
        </div>
    );
}