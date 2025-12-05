import Layout from "@/components/layout/Layout";
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
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
import { Clock, CheckCircle2, AlertCircle, FileDown } from "lucide-react";

export default function TimeManagement() {
  const timeEntries = [
    { name: "Davina Williams", date: "Oct 24, 2025", in: "08:02 AM", out: "05:05 PM", total: "9h 03m", status: "On Time" },
    { name: "Marcus Brown", date: "Oct 24, 2025", in: "08:45 AM", out: "05:00 PM", total: "8h 15m", status: "Late" },
    { name: "Sanjay Patel", date: "Oct 24, 2025", in: "07:55 AM", out: "06:30 PM", total: "10h 35m", status: "Overtime" },
    { name: "Sarah James", date: "Oct 24, 2025", in: "08:00 AM", out: "04:58 PM", total: "8h 58m", status: "On Time" },
    { name: "Michael Clarke", date: "Oct 24, 2025", in: "-", out: "-", total: "0h 00m", status: "Absent" },
  ];

  return (
    <Layout>
      <div className="space-y-8 animate-in fade-in duration-500">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-serif text-foreground">Time & Attendance</h1>
            <p className="text-muted-foreground mt-1">Monitor employee attendance, overtime, and punctuality.</p>
          </div>
          <div className="flex gap-3">
             <Button variant="outline">
              <FileDown className="mr-2 h-4 w-4" />
              Export Report
            </Button>
            <Button className="bg-primary hover:bg-primary/90">
              Approve Timesheets
            </Button>
          </div>
        </div>

        {/* Summary Cards */}
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
          <Card className="bg-white shadow-sm border-none">
            <CardHeader className="pb-2">
              <CardTitle className="text-sm font-medium text-muted-foreground">Present Today</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold font-serif text-foreground">38/42</div>
              <div className="w-full bg-muted h-1.5 mt-3 rounded-full overflow-hidden">
                <div className="bg-emerald-500 h-full rounded-full" style={{ width: '90%' }}></div>
              </div>
            </CardContent>
          </Card>
          
          <Card className="bg-white shadow-sm border-none">
            <CardHeader className="pb-2">
              <CardTitle className="text-sm font-medium text-muted-foreground">Late Arrivals</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold font-serif text-foreground">3</div>
              <p className="text-xs text-muted-foreground mt-1">Avg delay: 12 mins</p>
            </CardContent>
          </Card>

          <Card className="bg-white shadow-sm border-none">
            <CardHeader className="pb-2">
              <CardTitle className="text-sm font-medium text-muted-foreground">Overtime (Wk)</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold font-serif text-foreground">14.5h</div>
              <p className="text-xs text-muted-foreground mt-1">Est. Cost: $45,000 JMD</p>
            </CardContent>
          </Card>

          <Card className="bg-white shadow-sm border-none">
            <CardHeader className="pb-2">
              <CardTitle className="text-sm font-medium text-muted-foreground">Pending Corrections</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold font-serif text-foreground">8</div>
              <p className="text-xs text-muted-foreground mt-1">Missed punches</p>
            </CardContent>
          </Card>
        </div>

        {/* Timesheet Table */}
        <Card className="border-none shadow-md bg-white">
          <CardHeader>
            <div className="flex items-center justify-between">
              <div>
                 <CardTitle className="font-serif">Daily Logs</CardTitle>
                 <CardDescription>Attendance records for October 24, 2025</CardDescription>
              </div>
              <Tabs defaultValue="all" className="w-[300px]">
                <TabsList className="grid w-full grid-cols-3">
                  <TabsTrigger value="all">All</TabsTrigger>
                  <TabsTrigger value="late">Late</TabsTrigger>
                  <TabsTrigger value="absent">Absent</TabsTrigger>
                </TabsList>
              </Tabs>
            </div>
          </CardHeader>
          <CardContent>
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Employee</TableHead>
                  <TableHead>Date</TableHead>
                  <TableHead>Clock In</TableHead>
                  <TableHead>Clock Out</TableHead>
                  <TableHead>Total Hours</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead className="text-right">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {timeEntries.map((entry, i) => (
                  <TableRow key={i}>
                    <TableCell className="font-medium">{entry.name}</TableCell>
                    <TableCell>{entry.date}</TableCell>
                    <TableCell>{entry.in}</TableCell>
                    <TableCell>{entry.out}</TableCell>
                    <TableCell>{entry.total}</TableCell>
                    <TableCell>
                      <Badge 
                        variant="outline" 
                        className={
                          entry.status === "On Time" ? "bg-emerald-50 text-emerald-700 border-emerald-200" :
                          entry.status === "Late" ? "bg-amber-50 text-amber-700 border-amber-200" :
                          entry.status === "Overtime" ? "bg-blue-50 text-blue-700 border-blue-200" :
                          "bg-red-50 text-red-700 border-red-200"
                        }
                      >
                        {entry.status}
                      </Badge>
                    </TableCell>
                    <TableCell className="text-right">
                      <Button variant="ghost" size="sm">Edit</Button>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </CardContent>
        </Card>
      </div>
    </Layout>
  );
}