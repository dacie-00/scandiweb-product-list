import React from "react";
import {Product} from "@/models/product";
import {ProductCard} from "@/components/product-card";
import {Button} from "@/components/ui/button";

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
            <div className="flex items-center justify-between mb-4">
                <h1 className="text-2xl font-bold">Product List</h1>
                <div className="space-x-4">
                    <Button>ADD</Button>
                    <Button id="delete-product-btn" onClick={handleDelete}>MASS DELETE</Button>
                </div>
            </div>
            <div className="grid grid-cols-4 gap-4">
                {products.map((product) => (
                    <ProductCard key={product.id} product={product} />
                ))}
            </div>
        </div>
    );
}