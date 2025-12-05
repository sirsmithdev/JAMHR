import { useState } from "react";
import Layout from "@/components/layout/Layout";
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { 
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { 
  Table, 
  TableBody, 
  TableCell, 
  TableHead, 
  TableHeader, 
  TableRow 
} from "@/components/ui/table";
import { Calendar } from "@/components/ui/calendar";
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover";
import { Calendar as CalendarIcon, CheckCircle2, XCircle, Clock, Plus } from "lucide-react";
import { format } from "date-fns";
import { cn } from "@/lib/utils";

const leaveRequests = [
  { 
    id: 1, 
    name: "Marcus Brown", 
    type: "Vacation", 
    dates: "Oct 28 - Oct 30, 2025", 
    days: 3, 
    status: "Pending",
    reason: "Family trip"
  },
  { 
    id: 2, 
    name: "Sarah James", 
    type: "Sick Leave", 
    dates: "Oct 15, 2025", 
    days: 1, 
    status: "Approved",
    reason: "Medical appointment"
  },
  { 
    id: 3, 
    name: "Davina Williams", 
    type: "Personal", 
    dates: "Nov 05, 2025", 
    days: 1, 
    status: "Approved",
    reason: "Personal matters"
  },
];

export default function Leave() {
  const [date, setDate] = useState<Date | undefined>(new Date());
  const [requestOpen, setRequestOpen] = useState(false);

  return (
    <Layout>
      <div className="space-y-8 animate-in fade-in duration-500">
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <h1 className="text-3xl font-serif text-foreground">Leave Management</h1>
            <p className="text-muted-foreground mt-1">Manage time-off requests and balances.</p>
          </div>
          <Dialog open={requestOpen} onOpenChange={setRequestOpen}>
            <DialogTrigger asChild>
              <Button className="bg-primary hover:bg-primary/90">
                <Plus className="mr-2 h-4 w-4" /> Request Time Off
              </Button>
            </DialogTrigger>
            <DialogContent className="sm:max-w-[500px]">
              <DialogHeader>
                <DialogTitle className="font-serif">New Leave Request</DialogTitle>
                <DialogDescription>
                  Submit a request for time off. Pending manager approval.
                </DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="space-y-2">
                  <label className="text-sm font-medium">Leave Type</label>
                  <Select>
                    <SelectTrigger>
                      <SelectValue placeholder="Select type" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="vacation">Vacation (Paid)</SelectItem>
                      <SelectItem value="sick">Sick Leave</SelectItem>
                      <SelectItem value="personal">Personal / Compassionate</SelectItem>
                      <SelectItem value="maternity">Maternity / Paternity</SelectItem>
                      <SelectItem value="unpaid">Unpaid Leave</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                
                <div className="space-y-2">
                  <label className="text-sm font-medium">Start Date</label>
                  <Popover>
                    <PopoverTrigger asChild>
                      <Button
                        variant={"outline"}
                        className={cn(
                          "w-full justify-start text-left font-normal",
                          !date && "text-muted-foreground"
                        )}
                      >
                        <CalendarIcon className="mr-2 h-4 w-4" />
                        {date ? format(date, "PPP") : <span>Pick a date</span>}
                      </Button>
                    </PopoverTrigger>
                    <PopoverContent className="w-auto p-0">
                      <Calendar
                        mode="single"
                        selected={date}
                        onSelect={setDate}
                        initialFocus
                      />
                    </PopoverContent>
                  </Popover>
                </div>

                <div className="space-y-2">
                   <label className="text-sm font-medium">Duration (Days)</label>
                   <Select>
                    <SelectTrigger>
                      <SelectValue placeholder="1 Day" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="0.5">Half Day</SelectItem>
                      <SelectItem value="1">1 Day</SelectItem>
                      <SelectItem value="2">2 Days</SelectItem>
                      <SelectItem value="3">3 Days</SelectItem>
                      <SelectItem value="custom">Custom Range...</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>
              <DialogFooter>
                <Button variant="outline" onClick={() => setRequestOpen(false)}>Cancel</Button>
                <Button type="submit" onClick={() => setRequestOpen(false)}>Submit Request</Button>
              </DialogFooter>
            </DialogContent>
          </Dialog>
        </div>

        {/* Balances */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          <Card className="border-none shadow-sm bg-white">
            <CardHeader className="pb-2">
              <CardTitle className="text-sm font-medium text-muted-foreground">Vacation Balance</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="flex items-baseline gap-2">
                <span className="text-2xl font-bold font-serif text-foreground">12</span>
                <span className="text-sm text-muted-foreground">/ 15 days</span>
              </div>
              <div className="w-full bg-muted h-1.5 mt-3 rounded-full overflow-hidden">
                <div className="bg-emerald-500 h-full rounded-full" style={{ width: '80%' }}></div>
              </div>
            </CardContent>
          </Card>

          <Card className="border-none shadow-sm bg-white">
            <CardHeader className="pb-2">
              <CardTitle className="text-sm font-medium text-muted-foreground">Sick Leave</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="flex items-baseline gap-2">
                <span className="text-2xl font-bold font-serif text-foreground">8</span>
                <span className="text-sm text-muted-foreground">/ 10 days</span>
              </div>
              <div className="w-full bg-muted h-1.5 mt-3 rounded-full overflow-hidden">
                <div className="bg-blue-500 h-full rounded-full" style={{ width: '80%' }}></div>
              </div>
            </CardContent>
          </Card>

          <Card className="border-none shadow-sm bg-white">
            <CardHeader className="pb-2">
              <CardTitle className="text-sm font-medium text-muted-foreground">Pending Requests</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold font-serif text-amber-600">1</div>
              <p className="text-xs text-muted-foreground mt-1">Requires approval</p>
            </CardContent>
          </Card>
        </div>

        {/* Requests List */}
        <Card className="border-none shadow-md bg-white">
          <CardHeader>
            <CardTitle className="font-serif">Leave History</CardTitle>
          </CardHeader>
          <CardContent>
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Employee</TableHead>
                  <TableHead>Type</TableHead>
                  <TableHead>Dates</TableHead>
                  <TableHead>Days</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead className="text-right">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {leaveRequests.map((req) => (
                  <TableRow key={req.id} className="hover:bg-muted/5">
                    <TableCell className="font-medium">{req.name}</TableCell>
                    <TableCell>{req.type}</TableCell>
                    <TableCell>{req.dates}</TableCell>
                    <TableCell>{req.days}</TableCell>
                    <TableCell>
                      <Badge variant="secondary" className={cn(
                        "font-normal flex w-fit items-center gap-1",
                        req.status === "Approved" ? "bg-emerald-100 text-emerald-800" :
                        req.status === "Pending" ? "bg-amber-100 text-amber-800" :
                        "bg-red-100 text-red-800"
                      )}>
                        {req.status === "Approved" && <CheckCircle2 className="h-3 w-3" />}
                        {req.status === "Pending" && <Clock className="h-3 w-3" />}
                        {req.status === "Rejected" && <XCircle className="h-3 w-3" />}
                        {req.status}
                      </Badge>
                    </TableCell>
                    <TableCell className="text-right">
                      {req.status === "Pending" ? (
                        <div className="flex justify-end gap-2">
                          <Button size="sm" variant="outline" className="text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50">Approve</Button>
                          <Button size="sm" variant="outline" className="text-red-600 hover:text-red-700 hover:bg-red-50">Reject</Button>
                        </div>
                      ) : (
                        <Button variant="ghost" size="sm">Details</Button>
                      )}
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