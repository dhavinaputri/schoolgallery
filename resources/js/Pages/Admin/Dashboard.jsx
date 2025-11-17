import React from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Head } from '@inertiajs/react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { format } from 'date-fns';
import { id } from 'date-fns/locale';

export default function Dashboard({ stats, recentNews, recentGalleries, auth }) {
    return (
        <AdminLayout user={auth.user}>
            <Head title="Dashboard Admin" />
            
            <div className="space-y-4">
                <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium">Total Berita</CardTitle>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth="2"
                                className="h-4 w-4 text-muted-foreground"
                            >
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold">{stats.newsCount}</div>
                            <p className="text-xs text-muted-foreground">
                                {stats.publishedNews} berita dipublikasikan
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium">Total Galeri</CardTitle>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth="2"
                                className="h-4 w-4 text-muted-foreground"
                            >
                                <path d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z" />
                                <path d="m21 9-9-9-9 9" />
                                <path d="M9 2v12h6" />
                            </svg>
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold">{stats.galleryCount}</div>
                            <p className="text-xs text-muted-foreground">
                                {stats.publishedGalleries} galeri dipublikasikan
                            </p>
                        </CardContent>
                    </Card>
                </div>

                <div className="grid gap-4 md:grid-cols-2">
                    <Card>
                        <CardHeader>
                            <CardTitle>Berita Terbaru</CardTitle>
                            <CardDescription>5 berita terbaru yang ditambahkan</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Judul</TableHead>
                                        <TableHead>Status</TableHead>
                                        <TableHead className="text-right">Tanggal</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {recentNews.map((news) => (
                                        <TableRow key={news.id}>
                                            <TableCell className="font-medium">{news.title}</TableCell>
                                            <TableCell>
                                                <Badge variant={news.is_published ? 'default' : 'secondary'}>
                                                    {news.is_published ? 'Diterbitkan' : 'Draft'}
                                                </Badge>
                                            </TableCell>
                                            <TableCell className="text-right">
                                                {format(new Date(news.created_at), 'dd MMM yyyy', { locale: id })}
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                    {recentNews.length === 0 && (
                                        <TableRow>
                                            <TableCell colSpan={3} className="text-center py-4">
                                                Belum ada berita
                                            </TableCell>
                                        </TableRow>
                                    )}
                                </TableBody>
                            </Table>
                            <div className="mt-4">
                                <Button variant="outline" className="w-full" asChild>
                                    <a href={route('admin.news.index')}>Lihat Semua Berita</a>
                                </Button>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Galeri Terbaru</CardTitle>
                            <CardDescription>5 galeri terbaru yang ditambahkan</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Judul</TableHead>
                                        <TableHead>Status</TableHead>
                                        <TableHead className="text-right">Tanggal</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {recentGalleries.map((gallery) => (
                                        <TableRow key={gallery.id}>
                                            <TableCell className="font-medium">{gallery.title}</TableCell>
                                            <TableCell>
                                                <Badge variant={gallery.is_published ? 'default' : 'secondary'}>
                                                    {gallery.is_published ? 'Diterbitkan' : 'Draft'}
                                                </Badge>
                                            </TableCell>
                                            <TableCell className="text-right">
                                                {format(new Date(gallery.created_at), 'dd MMM yyyy', { locale: id })}
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                    {recentGalleries.length === 0 && (
                                        <TableRow>
                                            <TableCell colSpan={3} className="text-center py-4">
                                                Belum ada galeri
                                            </TableCell>
                                        </TableRow>
                                    )}
                                </TableBody>
                            </Table>
                            <div className="mt-4">
                                <Button variant="outline" className="w-full" asChild>
                                    <a href={route('admin.galleries.index')}>Lihat Semua Galeri</a>
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AdminLayout>
    );
}
