import { useState } from "react";
import Layout from "@/components/layout/Layout";
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
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
import { AlertTriangle, Plus, FileText, Eye, Calendar, MapPin } from "lucide-react";
import { cn } from "@/lib/utils";

const incidents = [
  { 
    id: "INC-2025-001", 
    type: "Workplace Injury", 
    severity: "Medium", 
    date: "Oct 12, 2025", 
    reporter: "Marcus Brown", 
    status: "Investigating",
    description: "Slipped on wet floor in break room."
  },
  { 
    id: "INC-2025-002", 
    type: "Equipment Failure", 
    severity: "Low", 
    date: "Oct 15, 2025", 
    reporter: "Sanjay Patel", 
    status: "Resolved",
    description: "Server room AC malfunctioned."
  },
  { 
    id: "INC-2025-003", 
    type: "Harassment", 
    severity: "High", 
    date: "Oct 18, 2025", 
    reporter: "Anonymous", 
    status: "Open",
    description: "Verbal altercation in sales department."
  },
];

export default function Incidents() {
  const [reportOpen, setReportOpen] = useState(false);

  return (
    <Layout>
      <div className="space-y-8 animate-in fade-in duration-500">
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <h1 className="text-3xl font-serif text-foreground">Incident Reporting</h1>
            <p className="text-muted-foreground mt-1">Log, track, and resolve workplace incidents and safety concerns.</p>
          </div>
          <Dialog open={reportOpen} onOpenChange={setReportOpen}>
            <DialogTrigger asChild>
              <Button className="bg-destructive hover:bg-destructive/90 text-white shadow-lg shadow-destructive/20">
                <AlertTriangle className="mr-2 h-4 w-4" /> Report Incident
              </Button>
            </DialogTrigger>
            <DialogContent className="sm:max-w-[600px]">
              <DialogHeader>
                <DialogTitle className="font-serif flex items-center gap-2 text-destructive">
                  <AlertTriangle className="h-5 w-5" />
                  New Incident Report
                </DialogTitle>
                <DialogDescription>
                  Please provide detailed information about the event. This record is confidential.
                </DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid grid-cols-2 gap-4">
                  <div className="space-y-2">
                    <Label>Incident Type</Label>
                    <Select>
                      <SelectTrigger>
                        <SelectValue placeholder="Select type" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="injury">Injury / Accident</SelectItem>
                        <SelectItem value="harassment">Harassment / Conduct</SelectItem>
                        <SelectItem value="security">Security Breach</SelectItem>
                        <SelectItem value="hazard">Safety Hazard</SelectItem>
                        <SelectItem value="other">Other</SelectItem>
                      </SelectContent>
                    </Select>
                  </div>
                  <div className="space-y-2">
                    <Label>Severity Level</Label>
                    <Select>
                      <SelectTrigger>
                        <SelectValue placeholder="Select severity" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="low">Low (Minor)</SelectItem>
                        <SelectItem value="medium">Medium (Requires Attention)</SelectItem>
                        <SelectItem value="high">High (Critical / Urgent)</SelectItem>
                      </SelectContent>
                    </Select>
                  </div>
                </div>
                
                <div className="grid grid-cols-2 gap-4">
                  <div className="space-y-2">
                    <Label>Date of Incident</Label>
                    <div className="relative">
                      <Calendar className="absolute left-3 top-2.5 h-4 w-4 text-muted-foreground" />
                      <Input type="date" className="pl-9" />
                    </div>
                  </div>
                   <div className="space-y-2">
                    <Label>Time</Label>
                    <Input type="time" />
                  </div>
                </div>

                <div className="space-y-2">
                  <Label>Location</Label>
                  <div className="relative">
                    <MapPin className="absolute left-3 top-2.5 h-4 w-4 text-muted-foreground" />
                    <Input placeholder="e.g. Main Lobby, Break Room, Remote" className="pl-9" />
                  </div>
                </div>

                <div className="space-y-2">
                  <Label>Description of Event</Label>
                  <Textarea 
                    placeholder="Describe exactly what happened, including who was involved..." 
                    className="min-h-[100px]"
                  />
                </div>

                <div className="space-y-2">
                  <Label>Witnesses (Optional)</Label>
                  <Input placeholder="Names of any witnesses" />
                </div>
              </div>
              <DialogFooter>
                <Button variant="outline" onClick={() => setReportOpen(false)}>Cancel</Button>
                <Button type="submit" className="bg-destructive hover:bg-destructive/90" onClick={() => setReportOpen(false)}>
                  Submit Report
                </Button>
              </DialogFooter>
            </DialogContent>
          </Dialog>
        </div>

        {/* Summary Metrics */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          <Card className="border-none shadow-sm bg-white">
            <CardHeader className="pb-2">
              <CardTitle className="text-sm font-medium text-muted-foreground">Open Cases</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold font-serif text-foreground">2</div>
              <p className="text-xs text-muted-foreground mt-1">Requiring action</p>
            </CardContent>
          </Card>
           <Card className="border-none shadow-sm bg-white">
            <CardHeader className="pb-2">
              <CardTitle className="text-sm font-medium text-muted-foreground">Safety Score</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold font-serif text-emerald-600">94%</div>
              <p className="text-xs text-muted-foreground mt-1">Last 30 days</p>
            </CardContent>
          </Card>
           <Card className="border-none shadow-sm bg-white">
            <CardHeader className="pb-2">
              <CardTitle className="text-sm font-medium text-muted-foreground">Days Incident Free</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold font-serif text-foreground">14</div>
              <p className="text-xs text-emerald-600 mt-1">Keep it up!</p>
            </CardContent>
          </Card>
        </div>

        {/* Incidents List */}
        <Card className="border-none shadow-md bg-white">
          <CardHeader>
            <CardTitle className="font-serif">Recent Logs</CardTitle>
          </CardHeader>
          <CardContent>
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>ID</TableHead>
                  <TableHead>Date</TableHead>
                  <TableHead>Type</TableHead>
                  <TableHead>Description</TableHead>
                  <TableHead>Severity</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead className="text-right">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {incidents.map((inc) => (
                  <TableRow key={inc.id} className="group cursor-pointer hover:bg-muted/5">
                    <TableCell className="font-mono text-xs text-muted-foreground">{inc.id}</TableCell>
                    <TableCell>{inc.date}</TableCell>
                    <TableCell className="font-medium">{inc.type}</TableCell>
                    <TableCell className="max-w-[300px] truncate text-muted-foreground">{inc.description}</TableCell>
                    <TableCell>
                      <Badge variant="outline" className={cn(
                        "font-normal",
                        inc.severity === "High" ? "border-red-200 text-red-700 bg-red-50" :
                        inc.severity === "Medium" ? "border-amber-200 text-amber-700 bg-amber-50" :
                        "border-slate-200 text-slate-700 bg-slate-50"
                      )}>
                        {inc.severity}
                      </Badge>
                    </TableCell>
                    <TableCell>
                      <div className="flex items-center gap-2">
                        <div className={cn("h-2 w-2 rounded-full", 
                          inc.status === "Open" ? "bg-red-500 animate-pulse" :
                          inc.status === "Investigating" ? "bg-blue-500" :
                          "bg-emerald-500"
                        )} />
                        <span>{inc.status}</span>
                      </div>
                    </TableCell>
                    <TableCell className="text-right">
                      <Button variant="ghost" size="sm">
                        <Eye className="h-4 w-4 text-muted-foreground" />
                        <span className="sr-only">View</span>
                      </Button>
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