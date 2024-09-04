import {createRootRoute, Link, Outlet} from '@tanstack/react-router'
import {TanStackRouterDevtools} from '@tanstack/router-devtools'

export const Route = createRootRoute({
    component: () => (
        <div className="mx-auto max-w-4xl">
            <div className="p-2 flex gap-2">
                <Link to="/" className="[&.active]:font-bold">
                    Home
                </Link>{' '}
                <Link to="/addProduct" className="[&.active]:font-bold">
                    Add Product
                </Link>{' '}
            </div>
            <hr/>
            <Outlet/>
            <TanStackRouterDevtools/>
        </div>
    ),
})
