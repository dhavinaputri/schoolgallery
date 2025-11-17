import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { Sidebar, Menu, MenuItem, SubMenu } from 'react-pro-sidebar';
import { LayoutDashboard, Newspaper, Image, Settings, Menu as MenuIcon, X, LogOut, User } from 'lucide-react';

export default function AdminLayout({ children, user }) {
    const [collapsed, setCollapsed] = useState(false);
    const [toggled, setToggled] = useState(false);

    const handleCollapse = () => setCollapsed(!collapsed);
    const handleToggle = () => setToggled(!toggled);
    const closeSidebar = () => setToggled(false);

    return (
        <div className="flex h-screen bg-gray-50">
            {/* Mobile sidebar overlay */}
            {toggled && (
                <div 
                    className="fixed inset-0 bg-black/50 z-10 md:hidden"
                    onClick={closeSidebar}
                />
            )}

            {/* Sidebar */}
            <div 
                className={`fixed inset-y-0 left-0 z-20 w-64 transform ${toggled ? 'translate-x-0' : '-translate-x-full'} transition-transform duration-300 ease-in-out md:relative md:translate-x-0`}
            >
                <Sidebar 
                    collapsed={collapsed} 
                    backgroundColor="#1e40af"
                    rootStyles={{
                        color: '#fff',
                        borderRight: 'none',
                        height: '100vh',
                        position: 'fixed',
                        width: collapsed ? '80px' : '256px',
                        transition: 'width 0.3s ease-in-out',
                    }}
                >
                    <div className="p-4 flex items-center justify-between">
                        {!collapsed && <h1 className="text-xl font-bold text-white">Admin Panel</h1>}
                        <button 
                            onClick={handleCollapse}
                            className="text-white hover:bg-blue-700 p-1 rounded-md"
                        >
                            {collapsed ? <MenuIcon size={20} /> : <X size={20} />}
                        </button>
                    </div>

                    <div className="px-4 py-2 mb-4">
                        <div className="flex items-center space-x-3">
                            <div className="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center">
                                <User className="h-5 w-5 text-white" />
                            </div>
                            {!collapsed && (
                                <div className="overflow-hidden">
                                    <p className="text-sm font-medium text-white truncate">{user?.name || 'Admin'}</p>
                                    <p className="text-xs text-blue-200 truncate">{user?.email || 'admin@example.com'}</p>
                                </div>
                            )}
                        </div>
                    </div>

                    <Menu
                        menuItemStyles={{
                            button: {
                                '&:hover': {
                                    backgroundColor: '#1e3a8a',
                                },
                                '&.active': {
                                    backgroundColor: '#1e3a8a',
                                    color: '#fff',
                                },
                            },
                        }}
                    >
                        <MenuItem 
                            component={<Link href={route('admin.dashboard')} />} 
                            icon={<LayoutDashboard size={18} />}
                            className="hover:bg-blue-800"
                        >
                            Dashboard
                        </MenuItem>
                        
                        <SubMenu 
                            label="Berita" 
                            icon={<Newspaper size={18} />}
                            className="hover:bg-blue-800"
                        >
                            <MenuItem component={<Link href={route('admin.news.create')} />}>
                                Tambah Berita
                            </MenuItem>
                            <MenuItem component={<Link href={route('admin.news.index')} />}>
                                Daftar Berita
                            </MenuItem>
                        </SubMenu>
                        
                        <SubMenu 
                            label="Galeri" 
                            icon={<Image size={18} />}
                            className="hover:bg-blue-800"
                        >
                            <MenuItem component={<Link href={route('admin.galleries.create')} />}>
                                Tambah Galeri
                            </MenuItem>
                            <MenuItem component={<Link href={route('admin.galleries.index')} />}>
                                Daftar Galeri
                            </MenuItem>
                        </SubMenu>
                        
                        <div className="border-t border-blue-700 my-2"></div>
                        
                        <MenuItem 
                            component={<Link href={route('admin.school-profile.edit')} />} 
                            icon={<Settings size={18} />}
                            className="hover:bg-blue-800"
                        >
                            Pengaturan Sekolah
                        </MenuItem>
                        
                        <MenuItem 
                            component={<Link href={route('admin.logout')} method="post" as="button" type="button" />} 
                            icon={<LogOut size={18} />}
                            className="hover:bg-blue-800 text-red-400 hover:text-red-300"
                        >
                            Keluar
                        </MenuItem>
                    </Menu>
                </Sidebar>
            </div>

            {/* Main Content */}
            <div className="flex-1 flex flex-col overflow-hidden">
                {/* Top Navigation */}
                <header className="bg-white shadow-sm z-10">
                    <div className="flex items-center justify-between px-6 py-4">
                        <button 
                            onClick={handleToggle}
                            className="text-gray-500 hover:text-gray-700 md:hidden"
                        >
                            <MenuIcon className="h-6 w-6" />
                        </button>
                        
                        <div className="flex items-center space-x-4">
                            <div className="relative">
                                <div className="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white">
                                    {user?.name?.charAt(0) || 'A'}
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                {/* Page Content */}
                <main className="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-50">
                    {children}
                </main>
            </div>
        </div>
    );
}
