import Layout from "@/components/layout/Layout";
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from "@/components/ui/accordion";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Briefcase, AlertCircle, CheckCircle2, FileText, ExternalLink, ShieldCheck } from "lucide-react";

export default function Compliance() {
  return (
    <Layout>
      <div className="space-y-8 animate-in fade-in duration-500">
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <h1 className="text-3xl font-serif text-foreground">Labor Laws & Compliance</h1>
            <p className="text-muted-foreground mt-1">Stay compliant with Jamaican Labor Laws and Statutory Regulations.</p>
          </div>
          <div className="flex gap-2">
             <Button variant="outline" className="gap-2">
              <ExternalLink className="h-4 w-4" />
              Ministry of Labour
            </Button>
            <Button className="bg-primary hover:bg-primary/90 gap-2">
              <ShieldCheck className="h-4 w-4" />
              Run Audit
            </Button>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div className="lg:col-span-2 space-y-8">
            
            {/* Critical Alerts */}
            <Alert className="bg-amber-50 border-amber-200 text-amber-900">
              <AlertCircle className="h-4 w-4 text-amber-600" />
              <AlertTitle className="text-amber-800 font-semibold">Minimum Wage Update</AlertTitle>
              <AlertDescription className="text-amber-800/80 mt-1">
                Effective June 1, 2025, the National Minimum Wage has increased to JMD $15,000 per 40-hour work week. Please update payroll settings.
              </AlertDescription>
            </Alert>

            {/* Statutory Deductions Guide */}
            <Card className="border-none shadow-md bg-white">
              <CardHeader>
                <CardTitle className="font-serif flex items-center gap-2">
                  <Briefcase className="h-5 w-5 text-primary" />
                  Statutory Contributions Guide (2025)
                </CardTitle>
                <CardDescription>Current rates and caps for payroll processing</CardDescription>
              </CardHeader>
              <CardContent className="space-y-4">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div className="p-4 rounded-lg border bg-slate-50">
                    <div className="flex justify-between items-start mb-2">
                      <h3 className="font-semibold text-foreground">NHT</h3>
                      <Badge>Mandatory</Badge>
                    </div>
                    <p className="text-sm text-muted-foreground mb-3">National Housing Trust</p>
                    <ul className="text-sm space-y-1">
                      <li className="flex justify-between"><span>Employee:</span> <span className="font-medium">2%</span></li>
                      <li className="flex justify-between"><span>Employer:</span> <span className="font-medium">3%</span></li>
                    </ul>
                  </div>
                  
                  <div className="p-4 rounded-lg border bg-slate-50">
                    <div className="flex justify-between items-start mb-2">
                      <h3 className="font-semibold text-foreground">NIS</h3>
                      <Badge>Capped</Badge>
                    </div>
                    <p className="text-sm text-muted-foreground mb-3">National Insurance Scheme</p>
                    <ul className="text-sm space-y-1">
                      <li className="flex justify-between"><span>Employee:</span> <span className="font-medium">3%</span></li>
                      <li className="flex justify-between"><span>Employer:</span> <span className="font-medium">3%</span></li>
                      <li className="text-xs text-muted-foreground mt-2 pt-2 border-t">Cap: JMD $5M / year</li>
                    </ul>
                  </div>

                  <div className="p-4 rounded-lg border bg-slate-50">
                    <div className="flex justify-between items-start mb-2">
                      <h3 className="font-semibold text-foreground">Ed Tax</h3>
                      <Badge>Mandatory</Badge>
                    </div>
                    <p className="text-sm text-muted-foreground mb-3">Education Tax</p>
                    <ul className="text-sm space-y-1">
                      <li className="flex justify-between"><span>Employee:</span> <span className="font-medium">2.25%</span></li>
                      <li className="flex justify-between"><span>Employer:</span> <span className="font-medium">3.5%</span></li>
                    </ul>
                  </div>

                  <div className="p-4 rounded-lg border bg-slate-50">
                    <div className="flex justify-between items-start mb-2">
                      <h3 className="font-semibold text-foreground">HEART</h3>
                      <Badge variant="secondary">Employer Only</Badge>
                    </div>
                    <p className="text-sm text-muted-foreground mb-3">HEART Trust / NTA</p>
                    <ul className="text-sm space-y-1">
                      <li className="flex justify-between"><span>Employee:</span> <span className="font-medium">0%</span></li>
                      <li className="flex justify-between"><span>Employer:</span> <span className="font-medium">3%</span></li>
                    </ul>
                  </div>
                </div>
              </CardContent>
            </Card>

            {/* Labor Laws Accordion */}
            <Card className="border-none shadow-md bg-white">
              <CardHeader>
                <CardTitle className="font-serif">Quick Reference: Employment Rights</CardTitle>
                <CardDescription>Key provisions from the Employment (Termination and Redundancy Payments) Act</CardDescription>
              </CardHeader>
              <CardContent>
                <Accordion type="single" collapsible className="w-full">
                  <AccordionItem value="item-1">
                    <AccordionTrigger>Notice Period Requirements</AccordionTrigger>
                    <AccordionContent className="text-muted-foreground text-sm leading-relaxed">
                      <p className="mb-2">Employees are entitled to notice based on length of service:</p>
                      <ul className="list-disc pl-5 space-y-1">
                        <li>Up to 5 years: 2 weeks notice</li>
                        <li>5 to 10 years: 4 weeks notice</li>
                        <li>10 to 15 years: 6 weeks notice</li>
                        <li>Over 15 years: 8 weeks notice</li>
                      </ul>
                    </AccordionContent>
                  </AccordionItem>
                  <AccordionItem value="item-2">
                    <AccordionTrigger>Sick Leave Entitlement</AccordionTrigger>
                    <AccordionContent className="text-muted-foreground text-sm leading-relaxed">
                      Employees are entitled to 10 days of paid sick leave per year after their first year of employment. For the first year, it is calculated at 1 day for every 22 days worked.
                    </AccordionContent>
                  </AccordionItem>
                  <AccordionItem value="item-3">
                    <AccordionTrigger>Vacation Leave</AccordionTrigger>
                    <AccordionContent className="text-muted-foreground text-sm leading-relaxed">
                      Standard entitlement is 2 weeks (10 working days) paid vacation annually. This typically increases to 3 weeks after 10 years of service.
                    </AccordionContent>
                  </AccordionItem>
                   <AccordionItem value="item-4">
                    <AccordionTrigger>Maternity Leave</AccordionTrigger>
                    <AccordionContent className="text-muted-foreground text-sm leading-relaxed">
                      Entitlement is 12 weeks maternity leave, with the first 8 weeks being paid. Employees must have worked for at least 12 months to qualify for paid leave.
                    </AccordionContent>
                  </AccordionItem>
                </Accordion>
              </CardContent>
            </Card>
          </div>

          {/* Sidebar Resources */}
          <div className="space-y-6">
            <Card className="bg-white border shadow-sm">
              <CardHeader>
                <CardTitle className="text-lg font-serif">Downloadable Forms</CardTitle>
              </CardHeader>
              <CardContent className="space-y-2">
                <Button variant="ghost" className="w-full justify-start h-auto py-3 text-left font-normal hover:bg-slate-50 border border-transparent hover:border-slate-200">
                  <FileText className="mr-3 h-5 w-5 text-primary" />
                  <div>
                    <div className="font-medium text-foreground">SO1 Form</div>
                    <div className="text-xs text-muted-foreground">Monthly Statutory Remittance</div>
                  </div>
                </Button>
                <Button variant="ghost" className="w-full justify-start h-auto py-3 text-left font-normal hover:bg-slate-50 border border-transparent hover:border-slate-200">
                  <FileText className="mr-3 h-5 w-5 text-primary" />
                  <div>
                    <div className="font-medium text-foreground">P24 Form</div>
                    <div className="text-xs text-muted-foreground">Annual Certificate of Pay</div>
                  </div>
                </Button>
                <Button variant="ghost" className="w-full justify-start h-auto py-3 text-left font-normal hover:bg-slate-50 border border-transparent hover:border-slate-200">
                   <FileText className="mr-3 h-5 w-5 text-primary" />
                  <div>
                    <div className="font-medium text-foreground">P45 Form</div>
                    <div className="text-xs text-muted-foreground">Termination Certificate</div>
                  </div>
                </Button>
              </CardContent>
            </Card>

            <Card className="bg-emerald-50 border-emerald-100 shadow-none">
               <CardContent className="pt-6">
                <div className="flex items-start gap-3">
                  <CheckCircle2 className="h-5 w-5 text-emerald-600 mt-0.5" />
                  <div className="space-y-2">
                    <h4 className="font-semibold text-emerald-900">Compliance Status</h4>
                    <p className="text-sm text-emerald-800">
                      Your organization is currently meeting all statutory filing requirements for the current fiscal year.
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