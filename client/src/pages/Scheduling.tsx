import Layout from "@/components/layout/Layout";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Badge } from "@/components/ui/badge";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { ChevronLeft, ChevronRight, Plus, Calendar as CalendarIcon } from "lucide-react";

const employees = [
  { id: 1, name: "Davina Williams", role: "Manager", avatar: "DW" },
  { id: 2, name: "Marcus Brown", role: "Sales Assoc.", avatar: "MB" },
  { id: 3, name: "Sanjay Patel", role: "Developer", avatar: "SP" },
  { id: 4, name: "Sarah James", role: "Support", avatar: "SJ" },
];

const shifts = [
  { day: 1, empId: 1, time: "09:00 - 17:00", type: "Office" },
  { day: 1, empId: 2, time: "08:00 - 16:00", type: "Floor" },
  { day: 1, empId: 4, time: "10:00 - 18:00", type: "Remote" },
  
  { day: 2, empId: 1, time: "09:00 - 17:00", type: "Office" },
  { day: 2, empId: 2, time: "08:00 - 16:00", type: "Floor" },
  { day: 2, empId: 3, time: "09:00 - 17:00", type: "Dev" },
  
  { day: 3, empId: 1, time: "09:00 - 17:00", type: "Office" },
  { day: 3, empId: 2, time: "08:00 - 16:00", type: "Floor" },
  { day: 3, empId: 4, time: "10:00 - 18:00", type: "Remote" },

  { day: 4, empId: 3, time: "09:00 - 17:00", type: "Dev" },
  { day: 4, empId: 4, time: "10:00 - 18:00", type: "Remote" },
];

const days = ["Mon 20", "Tue 21", "Wed 22", "Thu 23", "Fri 24", "Sat 25", "Sun 26"];

export default function Scheduling() {
  return (
    <Layout>
      <div className="space-y-8 animate-in fade-in duration-500">
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <h1 className="text-3xl font-serif text-foreground">Shift Scheduling</h1>
            <p className="text-muted-foreground mt-1">Plan and distribute shifts for the week of Oct 20 - Oct 26.</p>
          </div>
          <div className="flex items-center gap-3">
            <div className="flex items-center bg-white border rounded-md shadow-sm px-1">
              <Button variant="ghost" size="icon" className="h-8 w-8"><ChevronLeft className="h-4 w-4" /></Button>
              <span className="text-sm font-medium px-2">Oct 20 - 26</span>
              <Button variant="ghost" size="icon" className="h-8 w-8"><ChevronRight className="h-4 w-4" /></Button>
            </div>
            <Button className="bg-primary hover:bg-primary/90">
              <Plus className="mr-2 h-4 w-4" /> Add Shift
            </Button>
            <Button variant="outline">Publish</Button>
          </div>
        </div>

        <Card className="border-none shadow-md bg-white overflow-hidden">
          <CardHeader className="border-b bg-muted/30 pb-4">
            <div className="flex items-center gap-4">
              <Select defaultValue="all">
                <SelectTrigger className="w-[180px]">
                  <SelectValue placeholder="Department" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="all">All Departments</SelectItem>
                  <SelectItem value="sales">Sales</SelectItem>
                  <SelectItem value="support">Support</SelectItem>
                </SelectContent>
              </Select>
              <div className="flex items-center gap-4 text-sm text-muted-foreground ml-auto">
                <div className="flex items-center gap-2">
                  <div className="w-3 h-3 rounded-full bg-emerald-100 border border-emerald-200"></div>
                  <span>Published</span>
                </div>
                <div className="flex items-center gap-2">
                  <div className="w-3 h-3 rounded-full bg-amber-100 border border-amber-200"></div>
                  <span>Draft</span>
                </div>
              </div>
            </div>
          </CardHeader>
          
          <div className="overflow-x-auto">
            <div className="min-w-[1000px]">
              {/* Header Row */}
              <div className="grid grid-cols-8 border-b">
                <div className="p-4 font-medium text-sm text-muted-foreground bg-muted/10 border-r sticky left-0 z-10 bg-white w-48">Employee</div>
                {days.map((day, i) => (
                  <div key={i} className="p-4 text-center border-r last:border-r-0">
                    <div className="font-medium text-foreground">{day}</div>
                  </div>
                ))}
              </div>

              {/* Employee Rows */}
              {employees.map((emp) => (
                <div key={emp.id} className="grid grid-cols-8 border-b hover:bg-muted/5 transition-colors">
                  <div className="p-4 border-r sticky left-0 bg-white z-10 flex items-center gap-3 w-48">
                    <Avatar className="h-8 w-8">
                      <AvatarFallback className="bg-primary/10 text-primary text-xs">{emp.avatar}</AvatarFallback>
                    </Avatar>
                    <div>
                      <div className="font-medium text-sm">{emp.name}</div>
                      <div className="text-xs text-muted-foreground">{emp.role}</div>
                    </div>
                  </div>
                  
                  {days.map((_, dayIndex) => {
                    const shift = shifts.find(s => s.empId === emp.id && s.day === dayIndex + 1);
                    return (
                      <div key={dayIndex} className="p-2 border-r last:border-r-0 h-24 relative group">
                        {shift ? (
                          <div className="h-full w-full rounded-md bg-emerald-50 border border-emerald-100 p-2 cursor-pointer hover:bg-emerald-100 hover:border-emerald-200 transition-all group-hover:shadow-sm">
                            <div className="text-xs font-bold text-emerald-900">{shift.time}</div>
                            <div className="text-[10px] text-emerald-700 mt-1 bg-emerald-200/50 inline-block px-1.5 py-0.5 rounded">{shift.type}</div>
                          </div>
                        ) : (
                          <div className="h-full w-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                             <Button variant="ghost" size="icon" className="h-8 w-8 rounded-full text-muted-foreground hover:text-primary hover:bg-primary/10">
                               <Plus className="h-4 w-4" />
                             </Button>
                          </div>
                        )}
                      </div>
                    );
                  })}
                </div>
              ))}
              
              {/* Unassigned Row */}
              <div className="grid grid-cols-8 bg-muted/5">
                <div className="p-4 border-r sticky left-0 bg-muted/5 z-10 w-48 font-medium text-sm text-muted-foreground">
                  Open Shifts
                </div>
                 {days.map((_, i) => (
                  <div key={i} className="p-2 border-r last:border-r-0 h-16"></div>
                ))}
              </div>

            </div>
          </div>
        </Card>
      </div>
    </Layout>
  );
}