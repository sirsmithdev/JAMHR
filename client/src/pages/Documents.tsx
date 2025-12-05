import { useState } from "react";
import Layout from "@/components/layout/Layout";
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Badge } from "@/components/ui/badge";
import { 
  Table, 
  TableBody, 
  TableCell, 
  TableHead, 
  TableHeader, 
  TableRow 
} from "@/components/ui/table";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { 
  FileText, 
  Upload, 
  Search, 
  MoreVertical, 
  Folder, 
  Download, 
  Eye,
  FileCheck
} from "lucide-react";

const documents = [
  { id: 1, name: "Employment Contract - D. Williams", type: "PDF", size: "2.4 MB", uploaded: "Oct 01, 2025", category: "Contracts" },
  { id: 2, name: "Tax Registration (TRN) Form", type: "PDF", size: "1.1 MB", uploaded: "Sep 15, 2025", category: "Tax Forms" },
  { id: 3, name: "Employee Handbook v2025", type: "DOCX", size: "4.5 MB", uploaded: "Jan 10, 2025", category: "Policy" },
  { id: 4, name: "October Payroll Report", type: "XLSX", size: "850 KB", uploaded: "Oct 28, 2025", category: "Payroll" },
  { id: 5, name: "Safety Guidelines", type: "PDF", size: "3.2 MB", uploaded: "Jun 12, 2025", category: "Policy" },
];

export default function Documents() {
  return (
    <Layout>
      <div className="space-y-8 animate-in fade-in duration-500">
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <h1 className="text-3xl font-serif text-foreground">Document Management</h1>
            <p className="text-muted-foreground mt-1">Securely store and manage employee contracts, tax forms, and company policies.</p>
          </div>
          <Button className="bg-primary hover:bg-primary/90">
            <Upload className="mr-2 h-4 w-4" /> Upload Document
          </Button>
        </div>

        {/* Search and Filter Bar */}
        <div className="flex flex-col md:flex-row gap-4 items-center bg-white p-4 rounded-lg shadow-sm border">
          <div className="relative w-full md:w-96">
            <Search className="absolute left-3 top-2.5 h-4 w-4 text-muted-foreground" />
            <Input placeholder="Search files..." className="pl-9" />
          </div>
          <div className="flex gap-2 w-full md:w-auto md:ml-auto">
             <Button variant="outline" className="flex-1 md:flex-none">
               <Folder className="mr-2 h-4 w-4 text-amber-500" />
               New Folder
             </Button>
          </div>
        </div>

        <Tabs defaultValue="all" className="w-full">
          <TabsList>
            <TabsTrigger value="all">All Files</TabsTrigger>
            <TabsTrigger value="contracts">Contracts</TabsTrigger>
            <TabsTrigger value="policy">Policies</TabsTrigger>
            <TabsTrigger value="payroll">Payroll</TabsTrigger>
          </TabsList>

          <TabsContent value="all" className="mt-6">
            <Card className="border-none shadow-md bg-white">
              <CardHeader>
                <CardTitle className="font-serif">Recent Files</CardTitle>
                <CardDescription>Manage your organization's digital assets</CardDescription>
              </CardHeader>
              <CardContent>
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>Name</TableHead>
                      <TableHead>Category</TableHead>
                      <TableHead>Size</TableHead>
                      <TableHead>Uploaded</TableHead>
                      <TableHead className="text-right">Actions</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {documents.map((doc) => (
                      <TableRow key={doc.id} className="hover:bg-muted/5 group">
                        <TableCell>
                          <div className="flex items-center gap-3">
                            <div className="h-10 w-10 rounded bg-muted/20 flex items-center justify-center text-primary">
                              <FileText className="h-5 w-5" />
                            </div>
                            <div>
                              <div className="font-medium text-foreground group-hover:text-primary transition-colors cursor-pointer">{doc.name}</div>
                              <div className="text-xs text-muted-foreground uppercase">{doc.type}</div>
                            </div>
                          </div>
                        </TableCell>
                        <TableCell>
                          <Badge variant="secondary" className="font-normal bg-slate-100 text-slate-700">
                            {doc.category}
                          </Badge>
                        </TableCell>
                        <TableCell className="text-muted-foreground text-sm">{doc.size}</TableCell>
                        <TableCell className="text-muted-foreground text-sm">{doc.uploaded}</TableCell>
                        <TableCell className="text-right">
                          <div className="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <Button variant="ghost" size="icon" className="h-8 w-8 text-muted-foreground hover:text-primary">
                              <Eye className="h-4 w-4" />
                            </Button>
                            <Button variant="ghost" size="icon" className="h-8 w-8 text-muted-foreground hover:text-primary">
                              <Download className="h-4 w-4" />
                            </Button>
                            <Button variant="ghost" size="icon" className="h-8 w-8 text-muted-foreground">
                              <MoreVertical className="h-4 w-4" />
                            </Button>
                          </div>
                        </TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
              </CardContent>
            </Card>
          </TabsContent>
        </Tabs>

        {/* Quick Access / Templates */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          <Card className="bg-emerald-50 border-emerald-100 shadow-none">
            <CardHeader>
              <CardTitle className="text-emerald-800 flex items-center gap-2 text-lg">
                <FileCheck className="h-5 w-5" />
                Tax Forms Template
              </CardTitle>
            </CardHeader>
            <CardContent>
              <p className="text-sm text-emerald-700 mb-4">Download the official P24 and SO1 templates for annual returns.</p>
              <Button variant="outline" className="w-full border-emerald-200 text-emerald-700 hover:bg-emerald-100 hover:text-emerald-800">
                View Templates
              </Button>
            </CardContent>
          </Card>

           <Card className="bg-blue-50 border-blue-100 shadow-none">
            <CardHeader>
              <CardTitle className="text-blue-800 flex items-center gap-2 text-lg">
                <Folder className="h-5 w-5" />
                Employee Records
              </CardTitle>
            </CardHeader>
            <CardContent>
              <p className="text-sm text-blue-700 mb-4">Organized storage for all personnel files, ID scans, and contracts.</p>
              <Button variant="outline" className="w-full border-blue-200 text-blue-700 hover:bg-blue-100 hover:text-blue-800">
                Browse Records
              </Button>
            </CardContent>
          </Card>
        </div>

      </div>
    </Layout>
  );
}