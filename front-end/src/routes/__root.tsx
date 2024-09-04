import {createRootRoute, Link, Outlet} from '@tanstack/react-router'
import {TanStackRouterDevtools} from '@tanstack/router-devtools'
import {Footer} from "@/components/footer";

export const Route = createRootRoute({
    component: () => (
        <>
            <div className="mx-auto max-w-4xl pt-4">
                <Outlet/>
                <TanStackRouterDevtools/>
            </div>
            <Footer/>
        </>
    ),
})
