import React from "react";
import {Input} from "@/components/ui/input";
import {ProductCombobox} from "@/components/product-combobox";
import {Label} from "@/components/ui/label";
import {Button} from "@/components/ui/button";
import {Link} from "@tanstack/react-router";

const productTypes = [
    {
        value: "book",
        label: "Book",
    },
    {
        value: "dvd",
        label: "DVD",
    },
    {
        value: "furniture",
        label: "Furniture",
    },
]

const specialAttributes = {
    book: () =>
        <>
            <div className="w-100 flex items-center justify-between space-x-4">
                <Label htmlFor="weight" className="w-16">Weight (KG)</Label>
                <Input inputMode="text" placeholder="Weight" name="weight" id="weight"/>
            </div>
            <p>Please provide the weight in KG</p>
        </>,
    dvd: () =>
        <>
            <div className="w-100 flex items-center justify-between space-x-4">
                <Label htmlFor="size" className="w-16">Size (MB)</Label>
                <Input inputMode="decimal" placeholder="Size" name="size" id="size"/>
            </div>
            <p>Please provide the size in MB</p>
        </>,
    furniture: () =>
        <>
            <div className="space-y-6">
                <div className="w-100 flex items-center justify-between space-x-4">
                    <Label htmlFor="height" className="w-16">Height (CM)</Label>
                    <Input inputMode="decimal" placeholder="Height" name="height" id="height"/>
                </div>
                <div className="w-100 flex items-center justify-between space-x-4">
                    <Label htmlFor="width" className="w-16">Width (CM)</Label>
                    <Input inputMode="decimal" placeholder="Width" name="width" id="width"/>
                </div>
                <div className="w-100 flex items-center justify-between space-x-4">
                    <Label htmlFor="length" className="w-16">Length (CM)</Label>
                    <Input inputMode="decimal" placeholder="Length" name="length" id="length"/>
                </div>
            </div>
            <p>Please provide dimensions in HxWxL format</p>
        </>
};

type ProductFormProps = {
    onSubmit: (formData: any) => void
}

export function ProductForm({onSubmit}: ProductFormProps) {
    const [type, setType] = React.useState("")

    const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        const formData = new FormData(event.currentTarget);
        onSubmit(Object.fromEntries(formData.entries()));
    };

    return (
        <>
            <header className="flex items-center justify-between mb-4">
                <h1 className="text-2xl font-bold">Product Add</h1>
                <div className="space-x-4">
                    <Button form="product_form" type="submit">Save</Button>
                    <Link to="/">
                        <Button>Cancel</Button>
                    </Link>
                </div>
            </header>
            <hr className="h-px my-8 bg-slate-300 border-0 dark:bg-gray-700"></hr>
            <main>
                <form id="product_form" onSubmit={handleSubmit} className={"space-y-6 max-w-lg pt-4"}>
                    <div className="w-100 flex items-center justify-between space-x-4">
                        <Label htmlFor="sku" className="w-16">SKU</Label>
                        <Input inputMode="text" placeholder="SKU" name="sku" id="sku"/>
                    </div>
                    <div className="w-100 flex items-center justify-between space-x-4">
                        <Label htmlFor="name" className="w-16">name</Label>
                        <Input inputMode="text" placeholder="Name" name="name" id="name"/>
                    </div>
                    <div className="w-100 flex items-center justify-between space-x-4">
                        <Label htmlFor="price" className="w-16">Price ($)</Label>
                        <Input inputMode="decimal" placeholder="Price" name="price" id="price"/>
                    </div>
                    <div className="w-100 flex items-center justify-between space-x-4">
                        <Label htmlFor="productType">Type</Label>
                        <ProductCombobox onChange={setType} name="type" id="productType"/>
                    </div>
                    {type && specialAttributes[type] && specialAttributes[type]()}
                </form>
            </main>
        </>
    );
}