import Layout from "@/components/layout/Layout";
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Separator } from "@/components/ui/separator";
import { Badge } from "@/components/ui/badge";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { 
  Table, 
  TableBody, 
  TableCell, 
  TableHead, 
  TableHeader, 
  TableRow 
} from "@/components/ui/table";
import { Calculator, Download, Settings, Info } from "lucide-react";

export default function Payroll() {
  return (
    <Layout>
      <div className="space-y-8 animate-in fade-in duration-700">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-serif text-foreground">Payroll & Taxes</h1>
            <p className="text-muted-foreground mt-1">Manage Jamaican statutory deductions and payroll processing.</p>
          </div>
          <Button className="bg-primary hover:bg-primary/90">
            <Calculator className="mr-2 h-4 w-4" />
            Run Payroll
          </Button>
        </div>

        <Tabs defaultValue="overview" className="w-full">
          <TabsList className="grid w-full md:w-[400px] grid-cols-2">
            <TabsTrigger value="overview">Current Period</TabsTrigger>
            <TabsTrigger value="settings">Tax Configuration</TabsTrigger>
          </TabsList>

          <TabsContent value="overview" className="space-y-6 mt-6">
            {/* Payroll Summary Card */}
            <Card className="border-none shadow-md bg-white">
              <CardHeader>
                <CardTitle className="font-serif">October 15 - October 30, 2025</CardTitle>
                <CardDescription>Payroll Run #2025-20</CardDescription>
              </CardHeader>
              <CardContent>
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>Employee</TableHead>
                      <TableHead>Gross Pay (JMD)</TableHead>
                      <TableHead>NHT (2%)</TableHead>
                      <TableHead>NIS (3%)</TableHead>
                      <TableHead>Ed Tax (2.25%)</TableHead>
                      <TableHead className="text-right">Net Pay</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {[
                      { name: "Davina Williams", gross: 250000, nht: 5000, nis: 7500, ed: 5625, net: 206875 },
                      { name: "Marcus Brown", gross: 180000, nht: 3600, nis: 5400, ed: 4050, net: 148950 },
                      { name: "Sanjay Patel", gross: 320000, nht: 6400, nis: 9600, ed: 7200, net: 264800 },
                    ].map((row, i) => (
                      <TableRow key={i}>
                        <TableCell className="font-medium">{row.name}</TableCell>
                        <TableCell>
                          ${row.gross.toLocaleString()}
                        </TableCell>
                        <TableCell className="text-muted-foreground">${row.nht.toLocaleString()}</TableCell>
                        <TableCell className="text-muted-foreground">${row.nis.toLocaleString()}</TableCell>
                        <TableCell className="text-muted-foreground">${row.ed.toLocaleString()}</TableCell>
                        <TableCell className="text-right font-bold text-primary">
                          ${row.net.toLocaleString()}
                        </TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
                <div className="flex justify-end mt-6">
                  <Button variant="outline" className="mr-2">
                    <Download className="mr-2 h-4 w-4" />
                    Export Bank File
                  </Button>
                  <Button>Submit & Process</Button>
                </div>
              </CardContent>
            </Card>
          </TabsContent>

          <TabsContent value="settings" className="mt-6">
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
              <div className="lg:col-span-2 space-y-6">
                <Card className="border-none shadow-md bg-white">
                  <CardHeader>
                    <CardTitle className="font-serif flex items-center gap-2">
                      <Settings className="h-5 w-5" />
                      Statutory Deductions (Jamaica)
                    </CardTitle>
                    <CardDescription>Current rates as per Tax Administration Jamaica (TAJ)</CardDescription>
                  </CardHeader>
                  <CardContent className="space-y-6">
                    
                    {/* NHT Section */}
                    <div className="space-y-4">
                      <div className="flex items-center justify-between">
                        <div>
                          <h3 className="font-semibold text-foreground">National Housing Trust (NHT)</h3>
                          <p className="text-sm text-muted-foreground">Applied to gross salary</p>
                        </div>
                        <Badge variant="secondary" className="bg-emerald-100 text-emerald-800 hover:bg-emerald-100">Active</Badge>
                      </div>
                      <div className="grid grid-cols-2 gap-4">
                        <div className="space-y-2">
                          <Label>Employer Rate (%)</Label>
                          <Input defaultValue="3.0" />
                        </div>
                        <div className="space-y-2">
                          <Label>Employee Rate (%)</Label>
                          <Input defaultValue="2.0" />
                        </div>
                      </div>
                    </div>

                    <Separator />

                    {/* NIS Section */}
                    <div className="space-y-4">
                      <div className="flex items-center justify-between">
                        <div>
                          <h3 className="font-semibold text-foreground">National Insurance Scheme (NIS)</h3>
                          <p className="text-sm text-muted-foreground">Capped at JMD $5M annual income</p>
                        </div>
                        <Badge variant="secondary" className="bg-emerald-100 text-emerald-800 hover:bg-emerald-100">Active</Badge>
                      </div>
                      <div className="grid grid-cols-2 gap-4">
                        <div className="space-y-2">
                          <Label>Employer Rate (%)</Label>
                          <Input defaultValue="3.0" />
                        </div>
                        <div className="space-y-2">
                          <Label>Employee Rate (%)</Label>
                          <Input defaultValue="3.0" />
                        </div>
                      </div>
                    </div>

                    <Separator />

                    {/* Education Tax Section */}
                    <div className="space-y-4">
                      <div className="flex items-center justify-between">
                        <div>
                          <h3 className="font-semibold text-foreground">Education Tax</h3>
                          <p className="text-sm text-muted-foreground">Statutory education contribution</p>
                        </div>
                        <Badge variant="secondary" className="bg-emerald-100 text-emerald-800 hover:bg-emerald-100">Active</Badge>
                      </div>
                      <div className="grid grid-cols-2 gap-4">
                        <div className="space-y-2">
                          <Label>Employer Rate (%)</Label>
                          <Input defaultValue="3.5" />
                        </div>
                        <div className="space-y-2">
                          <Label>Employee Rate (%)</Label>
                          <Input defaultValue="2.25" />
                        </div>
                      </div>
                    </div>

                    <Separator />

                     {/* HEART Section */}
                     <div className="space-y-4">
                      <div className="flex items-center justify-between">
                        <div>
                          <h3 className="font-semibold text-foreground">HEART Trust / NTA</h3>
                          <p className="text-sm text-muted-foreground">Employer contribution only</p>
                        </div>
                        <Badge variant="secondary" className="bg-emerald-100 text-emerald-800 hover:bg-emerald-100">Active</Badge>
                      </div>
                      <div className="grid grid-cols-2 gap-4">
                        <div className="space-y-2">
                          <Label>Employer Rate (%)</Label>
                          <Input defaultValue="3.0" />
                        </div>
                        <div className="space-y-2">
                          <Label>Employee Rate (%)</Label>
                          <Input disabled value="N/A" className="bg-muted/50" />
                        </div>
                      </div>
                    </div>

                  </CardContent>
                </Card>
              </div>

              {/* Sidebar Info */}
              <div className="space-y-6">
                <Card className="bg-blue-50 border-blue-100 shadow-none">
                  <CardContent className="pt-6">
                    <div className="flex items-start gap-3">
                      <Info className="h-5 w-5 text-blue-600 mt-0.5" />
                      <div className="space-y-2">
                        <h4 className="font-semibold text-blue-900">Compliance Note</h4>
                        <p className="text-sm text-blue-800 leading-relaxed">
                          Ensure all SO1 forms are filed by the 14th of the following month to avoid penalties from Tax Administration Jamaica.
                        </p>
                      </div>
                    </div>
                  </CardContent>
                </Card>
              </div>
            </div>
          </TabsContent>
        </Tabs>
      </div>
    </Layout>
  );
}