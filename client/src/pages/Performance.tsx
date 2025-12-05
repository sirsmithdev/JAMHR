import { useState } from "react";
import Layout from "@/components/layout/Layout";
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { Badge } from "@/components/ui/badge";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Progress } from "@/components/ui/progress";
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
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { Star, TrendingUp, Users, Award, Target, CheckCircle2, Calendar } from "lucide-react";
import { cn } from "@/lib/utils";

const evaluations = [
  { 
    id: 1, 
    name: "Davina Williams", 
    role: "Manager", 
    date: "Oct 01, 2025", 
    rating: 4.8, 
    reviewer: "Board of Directors",
    status: "Completed",
    goals: 95
  },
  { 
    id: 2, 
    name: "Marcus Brown", 
    role: "Sales Assoc.", 
    date: "Sep 15, 2025", 
    rating: 3.5, 
    reviewer: "Davina Williams",
    status: "Needs Review",
    goals: 70
  },
  { 
    id: 3, 
    name: "Sanjay Patel", 
    role: "Developer", 
    date: "Oct 20, 2025", 
    rating: 4.2, 
    reviewer: "Davina Williams",
    status: "Draft",
    goals: 85
  },
];

export default function Performance() {
  const [reviewOpen, setReviewOpen] = useState(false);

  return (
    <Layout>
      <div className="space-y-8 animate-in fade-in duration-500">
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <h1 className="text-3xl font-serif text-foreground">Performance & Appraisals</h1>
            <p className="text-muted-foreground mt-1">Track KPIs, manage reviews, and foster employee growth.</p>
          </div>
          <Dialog open={reviewOpen} onOpenChange={setReviewOpen}>
            <DialogTrigger asChild>
              <Button className="bg-primary hover:bg-primary/90">
                <Star className="mr-2 h-4 w-4" /> Start Appraisal
              </Button>
            </DialogTrigger>
            <DialogContent className="sm:max-w-[600px]">
              <DialogHeader>
                <DialogTitle className="font-serif">New Performance Appraisal</DialogTitle>
                <DialogDescription>
                  Initiate a quarterly review cycle for an employee.
                </DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid grid-cols-2 gap-4">
                  <div className="space-y-2">
                    <Label>Employee</Label>
                    <Select>
                      <SelectTrigger>
                        <SelectValue placeholder="Select employee" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="1">Davina Williams</SelectItem>
                        <SelectItem value="2">Marcus Brown</SelectItem>
                        <SelectItem value="3">Sanjay Patel</SelectItem>
                      </SelectContent>
                    </Select>
                  </div>
                  <div className="space-y-2">
                    <Label>Review Cycle</Label>
                    <Select defaultValue="q4">
                      <SelectTrigger>
                        <SelectValue placeholder="Select cycle" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="q3">Q3 2025 (Jul-Sep)</SelectItem>
                        <SelectItem value="q4">Q4 2025 (Oct-Dec)</SelectItem>
                        <SelectItem value="annual">Annual 2025</SelectItem>
                      </SelectContent>
                    </Select>
                  </div>
                </div>

                <div className="space-y-4 border rounded-md p-4 bg-muted/10">
                  <h4 className="font-medium text-sm flex items-center gap-2">
                    <Target className="h-4 w-4 text-primary" /> Core Competencies
                  </h4>
                  
                  <div className="space-y-3">
                    <div className="space-y-1">
                      <div className="flex justify-between text-sm">
                        <Label>Job Knowledge</Label>
                        <span className="text-muted-foreground">1 - 5</span>
                      </div>
                      <Input type="range" min="1" max="5" step="0.5" className="cursor-pointer" />
                    </div>
                    <div className="space-y-1">
                      <div className="flex justify-between text-sm">
                        <Label>Quality of Work</Label>
                        <span className="text-muted-foreground">1 - 5</span>
                      </div>
                      <Input type="range" min="1" max="5" step="0.5" className="cursor-pointer" />
                    </div>
                    <div className="space-y-1">
                      <div className="flex justify-between text-sm">
                        <Label>Communication</Label>
                        <span className="text-muted-foreground">1 - 5</span>
                      </div>
                      <Input type="range" min="1" max="5" step="0.5" className="cursor-pointer" />
                    </div>
                  </div>
                </div>

                <div className="space-y-2">
                  <Label>Manager's Comments</Label>
                  <Textarea 
                    placeholder="Highlight achievements and areas for improvement..." 
                    className="min-h-[100px]"
                  />
                </div>
              </div>
              <DialogFooter>
                <Button variant="outline" onClick={() => setReviewOpen(false)}>Save Draft</Button>
                <Button type="submit" onClick={() => setReviewOpen(false)}>Submit Review</Button>
              </DialogFooter>
            </DialogContent>
          </Dialog>
        </div>

        {/* Overview Cards */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          <Card className="border-none shadow-sm bg-white">
            <CardHeader className="pb-2 flex flex-row items-center justify-between space-y-0">
              <CardTitle className="text-sm font-medium text-muted-foreground">Company Avg.</CardTitle>
              <TrendingUp className="h-4 w-4 text-emerald-600" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold font-serif text-foreground">4.2 / 5.0</div>
              <p className="text-xs text-emerald-600 mt-1">+0.3 from last quarter</p>
            </CardContent>
          </Card>
          
          <Card className="border-none shadow-sm bg-white">
            <CardHeader className="pb-2 flex flex-row items-center justify-between space-y-0">
              <CardTitle className="text-sm font-medium text-muted-foreground">Reviews Due</CardTitle>
              <Calendar className="h-4 w-4 text-secondary" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold font-serif text-foreground">12</div>
              <p className="text-xs text-muted-foreground mt-1">Due by Oct 31</p>
            </CardContent>
          </Card>

          <Card className="border-none shadow-sm bg-white">
            <CardHeader className="pb-2 flex flex-row items-center justify-between space-y-0">
              <CardTitle className="text-sm font-medium text-muted-foreground">Top Performer</CardTitle>
              <Award className="h-4 w-4 text-primary" />
            </CardHeader>
            <CardContent>
              <div className="flex items-center gap-2">
                <Avatar className="h-6 w-6">
                  <AvatarFallback className="text-xs bg-primary/10 text-primary">DW</AvatarFallback>
                </Avatar>
                <span className="font-medium">Davina Williams</span>
              </div>
              <p className="text-xs text-muted-foreground mt-1">Sales Dept • 4.8 Rating</p>
            </CardContent>
          </Card>
        </div>

        {/* Main Content Area */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Evaluation List */}
          <Card className="lg:col-span-2 border-none shadow-md bg-white">
            <CardHeader>
              <CardTitle className="font-serif">Recent Evaluations</CardTitle>
              <CardDescription>Appraisals conducted in the current fiscal year</CardDescription>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Employee</TableHead>
                    <TableHead>Date</TableHead>
                    <TableHead>Rating</TableHead>
                    <TableHead>Goals Met</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead className="text-right">Action</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {evaluations.map((evalItem) => (
                    <TableRow key={evalItem.id} className="hover:bg-muted/5">
                      <TableCell>
                        <div>
                          <div className="font-medium">{evalItem.name}</div>
                          <div className="text-xs text-muted-foreground">{evalItem.role}</div>
                        </div>
                      </TableCell>
                      <TableCell className="text-muted-foreground">{evalItem.date}</TableCell>
                      <TableCell>
                        <div className="flex items-center gap-1">
                          <span className="font-bold text-foreground">{evalItem.rating}</span>
                          <Star className="h-3 w-3 text-secondary fill-secondary" />
                        </div>
                      </TableCell>
                      <TableCell>
                        <div className="flex items-center gap-2">
                          <Progress value={evalItem.goals} className="w-16 h-2" />
                          <span className="text-xs text-muted-foreground">{evalItem.goals}%</span>
                        </div>
                      </TableCell>
                      <TableCell>
                        <Badge variant="secondary" className={cn(
                          "font-normal",
                          evalItem.status === "Completed" ? "bg-emerald-100 text-emerald-800" :
                          evalItem.status === "Needs Review" ? "bg-amber-100 text-amber-800" :
                          "bg-slate-100 text-slate-800"
                        )}>
                          {evalItem.status}
                        </Badge>
                      </TableCell>
                      <TableCell className="text-right">
                        <Button variant="ghost" size="sm">View</Button>
                      </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </CardContent>
          </Card>

          {/* Goals / KPI Side Panel */}
          <div className="space-y-6">
            <Card className="border-none shadow-md bg-white">
              <CardHeader>
                <CardTitle className="font-serif text-lg flex items-center gap-2">
                  <Target className="h-5 w-5 text-primary" />
                  Department Goals
                </CardTitle>
              </CardHeader>
              <CardContent className="space-y-6">
                <div className="space-y-2">
                  <div className="flex justify-between text-sm">
                    <span className="font-medium">Sales Targets</span>
                    <span className="text-emerald-600">92%</span>
                  </div>
                  <Progress value={92} className="h-2 bg-emerald-100 [&>div]:bg-emerald-500" />
                </div>
                <div className="space-y-2">
                  <div className="flex justify-between text-sm">
                    <span className="font-medium">Customer Satisfaction</span>
                    <span className="text-primary">78%</span>
                  </div>
                  <Progress value={78} className="h-2 bg-primary/20 [&>div]:bg-primary" />
                </div>
                <div className="space-y-2">
                  <div className="flex justify-between text-sm">
                    <span className="font-medium">Training Completion</span>
                    <span className="text-amber-600">45%</span>
                  </div>
                  <Progress value={45} className="h-2 bg-amber-100 [&>div]:bg-amber-500" />
                </div>

                <Button variant="outline" className="w-full mt-4">Manage KPIs</Button>
              </CardContent>
            </Card>

            <Card className="bg-blue-50 border-blue-100 shadow-none">
              <CardContent className="pt-6">
                <div className="flex items-start gap-3">
                  <CheckCircle2 className="h-5 w-5 text-blue-600 mt-0.5" />
                  <div className="space-y-2">
                    <h4 className="font-semibold text-blue-900">Upcoming Cycle</h4>
                    <p className="text-sm text-blue-800 leading-relaxed">
                      Q4 Performance Reviews begin on <strong>October 15th</strong>. Please ensure all self-assessments are submitted by Friday.
                    </p>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </Layout>
  );
}