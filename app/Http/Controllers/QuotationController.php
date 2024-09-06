<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use Carbon\Carbon;
use App\Models\Guest;
use App\Models\companys;
use App\Models\representative;
use App\Models\representative_phone;
use App\Models\company_fax;
use App\Models\company_phone;
use App\Models\master_promotion;
use App\Models\Freelancer_Member;
use App\Models\province;
use App\Models\amphures;
use App\Models\districts;
use App\Models\master_document;
use App\Models\master_product_item;
use App\Models\master_quantity;
use App\Models\master_unit;
use App\Models\document_quotation;
use App\Models\log;
use App\Models\Master_company;
use App\Models\phone_guest;
use Auth;
use App\Models\User;
use PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Models\master_document_sheet;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;
use App\Models\master_template;
use Illuminate\Support\Arr;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Mail\QuotationEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\master_document_email;
use App\Models\log_company;
class QuotationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userid = Auth::user()->id;
        $permissionid = Auth::user()->permission;
        $Quotation_IDs = Quotation::query()->pluck('Quotation_ID');
        $document = document_quotation::whereIn('Quotation_ID', $Quotation_IDs)->get();
        $document_IDs = $document->pluck('Quotation_ID');
        $missingQuotationIDs = $Quotation_IDs->diff($document_IDs);
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        Quotation::whereIn('Quotation_ID', $missingQuotationIDs)->delete();
        if ($user->permission == 0) {
            $Proposalcount = Quotation::query()->where('Operated_by',$userid)->count();
            $Proposal = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->paginate($perPage);
            $Pending = Quotation::query()->where('Operated_by',$userid)->whereIn('status_document',[1,3])->where('status_guest',0)->paginate($perPage);
            $Pendingcount = Quotation::query()->where('Operated_by',$userid)->whereIn('status_document',[1,3])->where('status_guest',0)->count();
            $Awaiting = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_document',2)->paginate($perPage);
            $Awaitingcount = Quotation::query()->where('Operated_by',$userid)->where('status_document',2)->count();
            $Approved = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_guest',1)->paginate($perPage);
            $Approvedcount = Quotation::query()->where('Operated_by',$userid)->where('status_guest',1)->count();
            $Reject = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_document',4)->paginate($perPage);
            $Rejectcount = Quotation::query()->where('Operated_by',$userid)->where('status_document',4)->count();
            $Cancel = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_document',0)->paginate($perPage);
            $Cancelcount = Quotation::query()->where('Operated_by',$userid)->where('status_document',0)->count();
            $User = User::where('permission',$permissionid)->where('id',$userid)->select('name','id','permission')->get();
        }
        elseif ($user->permission == 1 || $user->permission == 2) {
            $Proposalcount = Quotation::query()->count();
            $Proposal = Quotation::query()->orderBy('created_at', 'desc')->paginate($perPage);;
            $Pending = Quotation::query()->whereIn('status_document',[1,3])->where('status_guest',0)->paginate($perPage);
            $Pendingcount = Quotation::query()->whereIn('status_document',[1,3])->where('status_guest',0)->count();
            $Awaiting = Quotation::query()->where('status_document',2)->paginate($perPage);
            $Awaitingcount = Quotation::query()->where('status_document',2)->count();
            $Approved = Quotation::query()->where('status_guest',1)->paginate($perPage);
            $Approvedcount = Quotation::query()->where('status_guest',1)->count();
            $Reject = Quotation::query()->where('status_document',4)->paginate($perPage);
            $Rejectcount = Quotation::query()->where('status_document',4)->count();
            $Cancel = Quotation::query()->where('status_document',0)->paginate($perPage);
            $Cancelcount = Quotation::query()->where('status_document',0)->count();
            $User = User::select('name','id','permission')->whereIn('permission',[0,1,2])->get();
        }
        return view('quotation.index',compact('Proposalcount','Proposal','Awaitingcount','Awaiting','Pending','Pendingcount','Approved','Approvedcount','Rejectcount','Reject','Cancel','Cancelcount','User'));
    }
    public function SearchAll(Request $request){

        $checkin  = $request->checkin;
        $checkout  = $request->checkout;
        $checkbox  = $request->checkbox;
        $checkboxAll = $request->checkboxAll;
        $Usercheck = $request->User;
        $status = $request->status;
        $Filter = $request->Filter;
        $user = Auth::user();
        $userid = Auth::user()->id;
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        if ($checkin) {
            $checkinDate = Carbon::createFromFormat('d/m/Y', $checkin)->format('Y-m-d');
        }

        if ($checkout) {
            $checkoutDate = Carbon::createFromFormat('d/m/Y', $checkout)->format('Y-m-d');
        }
        if ($user->permission == 1) {
            $User = User::select('name','id')->whereIn('permission',[0,1,2])->get();
            $Proposalcount = Quotation::query()->count();

            if ($Filter == 'All') {
                $Proposal = Quotation::query()->orderBy('created_at', 'desc')->get();
            }elseif ($Filter == 'Nocheckin') {
                if ($Filter == 'Nocheckin'&&$checkin ==null&& $checkout == null) {
                    if ($Filter == 'Nocheckin'&&$Usercheck ==null&& $status == null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$Usercheck !==null&& $status == null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 1 && $Usercheck == null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->whereIn('status_document',[1,3])->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 3 && $Usercheck == null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('status_guest',1)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 2 && $Usercheck == null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('status_document',2)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 4 && $Usercheck == null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 0 && $Usercheck == null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 1 && $Usercheck !== null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->whereIn('status_document',[1,3])->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 3 && $Usercheck !== null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_guest',1)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 2 && $Usercheck !== null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',2)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 4 && $Usercheck !== null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 0 && $Usercheck !== null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
                    }
                }
            }elseif ($Filter == 'Checkin') {
                if ($checkin && $checkout &&$Usercheck ==null&& $status == null ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == null ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck ==null&& $status == 1 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->whereIn('status_document',[1,3])->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck ==null&& $status == 2 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('status_document',2)->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck ==null&& $status == 3 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('status_guest',1)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck ==null&& $status == 4 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('status_document',4)->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck ==null&& $status == 0 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('status_document',0)->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 1 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->whereIn('status_document',[1,3])->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 2 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',2)->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 3 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_guest',1)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 4 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',4)->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 0 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',0)->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }
            }elseif ($Filter == null) {
                if ($Usercheck) {
                    if ($Usercheck !== null && $status == null) {
                        $Proposal = Quotation::query()->orderBy('created_at', 'desc')->where('Operated_by',$Usercheck)->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 0) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 1) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->whereIn('status_document',[1,3])->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 2) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->where('status_document',2)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 3) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->where('status_guest',1)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 4) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
                    }
                }else {
                    if ($status == 0) {
                        if ($status == null) {
                            $Proposal = Quotation::query()->where('status_document',0)->paginate($perPage);
                        }else{
                            $Proposal = Quotation::query()->where('status_document',0)->paginate($perPage);
                        }
                    }elseif ($status == 1) {
                        $Proposal = Quotation::query()->whereIn('status_document',[1,3])->paginate($perPage);

                    }elseif ($status == 2) {
                        $Proposal = Quotation::query()->where('status_document',2)->paginate($perPage);
                    }elseif ($status == 3) {
                        $Proposal = Quotation::query()->where('status_guest',1)->paginate($perPage);

                    }elseif ($status == 4) {
                        $Proposal = Quotation::query()->where('status_document',4)->paginate($perPage);
                    }
                }
            }
            $Pending = Quotation::query()->whereIn('status_document',[1,3])->where('status_guest',0)->paginate($perPage);
            $Approved = Quotation::query()->where('status_guest',1)->paginate($perPage);
            $Pendingcount = Quotation::query()->whereIn('status_document',[1,3])->where('status_guest',0)->count();
            $Awaiting = Quotation::query()->where('status_document',2)->paginate($perPage);
            $Awaitingcount = Quotation::query()->where('status_document',2)->count();
            $Approvedcount = Quotation::query()->where('status_guest',1)->count();
            $Reject = Quotation::query()->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
            $Rejectcount = Quotation::query()->where('status_document',4)->count();
            $Cancel = Quotation::query()->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
            $Cancelcount = Quotation::query()->where('status_document',0)->count();
        }
        if ($user->permission == 0) {

            $User = User::select('name','id')->where('id',$userid)->get();
            if ($Filter == 'All') {
                $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->orderBy('created_at', 'desc')->paginate($perPage);
            }elseif ($Filter == 'Nocheckin') {
                if ($Filter == 'Nocheckin'&&$checkin ==null&& $checkout == null&&$status == null && $Usercheck !== null) {
                    $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($Filter == 'Nocheckin'&&$status == 1 && $Usercheck !== null) {
                    $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->whereIn('status_document',[1,3])->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($Filter == 'Nocheckin'&&$status == 3 && $Usercheck !== null) {
                    $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_guest',1)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($Filter == 'Nocheckin'&&$status == 2 && $Usercheck !== null) {
                    $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',2)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($Filter == 'Nocheckin'&&$status == 4 && $Usercheck !== null) {
                    $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($Filter == 'Nocheckin'&&$status == 0 && $Usercheck !== null) {
                    $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }
            }elseif ($Filter == 'Checkin') {
                if ($checkin && $checkout &&$Usercheck !==null&& $status == null ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 1 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->whereIn('status_document',[1,3])->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 2 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',2)->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 3 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_guest',1)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 4 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',4)->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 0 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',0)->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }
            }elseif ($Filter == null) {
                if ($Usercheck) {
                    if ($Usercheck !== null && $status == null) {
                        $Proposal = Quotation::query()->orderBy('created_at', 'desc')->where('Operated_by',$Usercheck)->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 0) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 1) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->whereIn('status_document',[1,3])->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 2) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->where('status_document',2)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 3) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->where('status_guest',1)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 4) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
                    }
                }
            }
            $Proposalcount = Quotation::query()->where('Operated_by',$userid)->count();
            $Pending = Quotation::query()->where('Operated_by',$userid)->whereIn('status_document',[1,3])->where('status_guest',0)->paginate($perPage);
            $Pendingcount = Quotation::query()->where('Operated_by',$userid)->whereIn('status_document',[1,3])->where('status_guest',0)->count();
            $Awaiting = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_document',2)->paginate($perPage);
            $Awaitingcount = Quotation::query()->where('Operated_by',$userid)->where('status_document',2)->count();
            $Approved = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_guest',1)->paginate($perPage);
            $Approvedcount = Quotation::query()->where('Operated_by',$userid)->where('status_guest',1)->count();
            $Reject = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_document',4)->paginate($perPage);
            $Rejectcount = Quotation::query()->where('Operated_by',$userid)->where('status_document',4)->count();
            $Cancel = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_document',0)->paginate($perPage);
            $Cancelcount = Quotation::query()->where('Operated_by',$userid)->where('status_document',0)->count();
        }
        return view('quotation.index',compact('Proposalcount','Proposal','Awaitingcount','Awaiting','Pending','Pendingcount','Approved','Approvedcount','Rejectcount','Reject','Cancel','Cancelcount'
        ,'User'));
    }
    public function  paginate_table_proposal(Request $request)
    {
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        if ($perPage == 10) {
            $data_query = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')
            ->limit($request->page.'0')
            ->get();
        } else {
            $data_query = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->paginate($perPage);
        }
        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";

                // สร้าง dropdown สำหรับการทำรายการ
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {

                    // สร้างสถานะการใช้งาน
                    if ($value->status_guest == 1) {
                        $btn_status = '<span class="badge rounded-pill bg-success">Approved</span>';
                    } else {
                        if ($value->status_document == 0) {
                            $btn_status = '<span class="badge rounded-pill bg-danger">Cancel</span>';
                        } elseif ($value->status_document == 1) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Pending</span>';
                        } elseif ($value->status_document == 2) {
                            $btn_status = '<span class="badge rounded-pill bg-warning">Awaiting Approval</span>';
                        } elseif ($value->status_document == 3) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Pending</span>';
                        } elseif ($value->status_document == 4) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color:#1d4ed8">Reject</span>';
                        } elseif ($value->status_document == 6) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Pending</span>';
                        }
                    }
                    $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                    $canViewProposal = Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                    $canEditProposal = Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);

                    $btn_action = '<div class="dropdown">';
                    $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                    $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';

                    if ($rolePermission > 0) {
                        if ($canViewProposal == 1) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href=\'' . url('/Proposal/view/' . $value->id) . '\'>View</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href=\'' . url('/Proposal/Quotation/cover/document/PDF/' . $value->id) . '\'>Export</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href=\'' . url('/Proposal/send/email/' . $value->id) . '\'>Send Email</a></li>';
                        }

                        if ($canEditProposal == 1 && (Auth::user()->id == $value->Operated_by || $rolePermission == 1 || $rolePermission == 3)) {
                            if (in_array($value->status_document, [1, 6, 3])) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href=\'' . url('/Quotation/edit/quotation/' . $value->id) . '\'>Edit</a></li>';
                            }

                            if ($value->status_document == 1 && $value->SpecialDiscountBath == 0 && $value->SpecialDiscount == 0) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Approved(' . $value->id . ')">Approved</a></li>';
                            } elseif ($value->status_document == 3 && $value->Confirm_by !== 0) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Approved(' . $value->id . ')">Approved</a></li>';
                            }
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href=\'' . url('/Quotation/view/quotation/LOG/' . $value->id) . '\'>LOG</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                        }
                    }

                    $btn_action .= '</ul>';
                    $btn_action .= '</div>';

                    $data[] = [
                        'number' => $key + 1,
                        'DummyNo' => $value->DummyNo == $value->Quotation_ID ? '-' : $value->DummyNo,
                        'Proposal_ID' => $value->Quotation_ID,
                        'Company_Name' => @$value->company->Company_Name,
                        'IssueDate' => $value->issue_date,
                        'ExpirationDate' => $value->Expirationdate,
                        'CheckIn' => $value->checkin ? \Carbon\Carbon::parse($value->checkin)->format('d/m/Y') : '-',
                        'CheckOut' => $value->checkout ? \Carbon\Carbon::parse($value->checkout)->format('d/m/Y') : '-',
                        'DiscountP' => $value->SpecialDiscount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                        'DiscountB' => $value->SpecialDiscountBath == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                        'Approve' => $value->Confirm_by == 'Auto' || $value->Confirm_by == '-' ? $value->Confirm_by : @$value->userConfirm->name,
                        'Operated' => @$value->userOperated->name,
                        'DocumentStatus' => $btn_status,
                        'btn_action' => $btn_action,
                    ];
                }
            }
        }
        // dd($data);
        return response()->json([
            'data' => $data,
        ]);
    }
    public function search_table_proposal(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;
        if ($search_value) {
            $data_query = Quotation::where('Quotation_ID', 'LIKE', '%'.$search_value.'%')
            ->orWhere('checkin', 'LIKE', '%'.$search_value.'%')
            ->orWhere('checkout', 'LIKE', '%'.$search_value.'%')
            ->orWhere('issue_date', 'LIKE', '%'.$search_value.'%')
            ->orWhere('Expirationdate', 'LIKE', '%'.$search_value.'%')
            ->where('Company_ID',$guest_profile)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->paginate($perPageS);
        }
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                if ($value->status_guest == 1) {
                    $btn_status = '<span class="badge rounded-pill bg-success">Approved</span>';
                } else {
                    if ($value->status_document == 0) {
                        $btn_status = '<span class="badge rounded-pill bg-danger">Cancel</span>';
                    } elseif ($value->status_document == 1) {
                        $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Pending</span>';
                    } elseif ($value->status_document == 2) {
                        $btn_status = '<span class="badge rounded-pill bg-warning">Awaiting Approval</span>';
                    } elseif ($value->status_document == 3) {
                        $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Pending</span>';
                    } elseif ($value->status_document == 4) {
                        $btn_status = '<span class="badge rounded-pill " style="background-color:#1d4ed8">Reject</span>';
                    } elseif ($value->status_document == 6) {
                        $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Pending</span>';
                    }
                }
                $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                $canViewProposal = Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                $canEditProposal = Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);

                $btn_action = '<div class="dropdown">';
                $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';

                if ($rolePermission > 0) {
                    if ($canViewProposal == 1) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href=\'' . url('/Proposal/view/' . $value->id) . '\'>View</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href=\'' . url('/Proposal/Quotation/cover/document/PDF/' . $value->id) . '\'>Export</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href=\'' . url('/Proposal/send/email/' . $value->id) . '\'>Send Email</a></li>';
                    }

                    if ($canEditProposal == 1 && (Auth::user()->id == $value->Operated_by || $rolePermission == 1 || $rolePermission == 3)) {
                        if (in_array($value->status_document, [1, 6, 3])) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href=\'' . url('/Quotation/edit/quotation/' . $value->id) . '\'>Edit</a></li>';
                        }

                        if ($value->status_document == 1 && $value->SpecialDiscountBath == 0 && $value->SpecialDiscount == 0) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Approved(' . $value->id . ')">Approved</a></li>';
                        } elseif ($value->status_document == 3 && $value->Confirm_by !== 0) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Approved(' . $value->id . ')">Approved</a></li>';
                        }
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href=\'' . url('/Quotation/view/quotation/LOG/' . $value->id) . '\'>LOG</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                    }
                }

                $btn_action .= '</ul>';
                $btn_action .= '</div>';
                $data[] = [
                    'number' => $key + 1,
                    'DummyNo' => $value->DummyNo == $value->Quotation_ID ? '-' : $value->DummyNo,
                    'Proposal_ID' => $value->Quotation_ID,
                    'Company_Name' => @$value->company->Company_Name,
                    'IssueDate' => $value->issue_date,
                    'ExpirationDate' => $value->Expirationdate,
                    'CheckIn' => $value->checkin ? \Carbon\Carbon::parse($value->checkin)->format('d/m/Y') : '-',
                    'CheckOut' => $value->checkout ? \Carbon\Carbon::parse($value->checkout)->format('d/m/Y') : '-',
                    'DiscountP' => $value->SpecialDiscount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                    'DiscountB' => $value->SpecialDiscountBath == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                    'Approve' => $value->Confirm_by == 'Auto' || $value->Confirm_by == '-' ? $value->Confirm_by : @$value->userConfirm->name,
                    'Operated' => @$value->userOperated->name,
                    'DocumentStatus' => $btn_status,
                    'btn_action' => $btn_action,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    //---------------------------สร้าง---------------------
    public function create()
    {
        $currentDate = Carbon::now();
        $ID = 'PD-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = Quotation::latest()->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;
        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $Issue_date = Carbon::parse($currentDate)->translatedFormat('d/m/Y');
        $Valid_Until = Carbon::parse($currentDate)->addDays(7)->translatedFormat('d/m/Y');
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $Quotation_ID = $ID.$year.$month.$newRunNumber;
        $Mevent = master_document::select('name_th','id','lavel')->where('status', '1')->where('Category','Mevent')->get();
        $Mvat = master_document::select('name_th','id','lavel')->where('status', '1')->where('Category','Mvat')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $Company = companys::select('Company_Name','id','Profile_ID')->get();
        $Guest = Guest::select('First_name','Last_name','id','Profile_ID')->get();
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        return view('quotation.create',compact('Quotation_ID','Company','Mevent','Freelancer_member','Issue_date','Valid_Until','Mvat','settingCompany','Guest'));
    }
    public function Contactcreate($companyID)
    {
        $company =  companys::where('Profile_ID',$companyID)->first();
        $Company_typeID=$company->Company_type;
        $CityID=$company->City;
        $amphuresID = $company->Amphures;
        $TambonID = $company->Tambon;
        $Company_type = master_document::where('id',$Company_typeID)->select('name_th','id')->first();
        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
        $company_fax = company_fax::where('Profile_ID',$companyID)->where('Sequence','main')->first();
        if (!$company_fax) {
            $company_fax = '-';
        }
        $company_phone = company_phone::where('Profile_ID',$companyID)->where('Sequence','main')->first();

        $Contact_names = representative::where('Company_ID', $companyID)
            ->where('status', 1)
            ->orderby('id', 'desc')
            ->first();
        $phone=$Contact_names->Profile_ID;
        $Contact_phones = representative_phone::where('Profile_ID',$phone)->where('Sequence','main')->first();
        return response()->json([
            'data' => $Contact_names,
            'Contact_phones' => $Contact_phones,
            'company'=>$company,
            'company_phone'=>$company_phone,
            'company_fax'=>$company_fax,
            'Company_type'=>$Company_type,
            'province'=>$provinceNames,
            'amphures'=>$amphuresID,
            'Tambon'=>$TambonID,
        ]);
    }
    public function Guestcreate($Guest){
        $Guest = Guest::where('Profile_ID',$Guest)->first();
        $Profile_ID=$Guest->Profile_ID;
        $Company_typeID=$Guest->preface;
        $CityID=$Guest->City;
        $amphuresID = $Guest->Amphures;
        $TambonID = $Guest->Tambon;
        $Company_type = master_document::where('id',$Company_typeID)->select('name_th','id')->first();
        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
        $phone = phone_guest::where('Profile_ID',$Profile_ID)->where('Sequence','main')->first();
        return response()->json([
            'data' => $Guest,
            'phone'=>$phone,
            'Company_type'=>$Company_type,
            'province'=>$provinceNames,
            'amphures'=>$amphuresID,
            'Tambon'=>$TambonID,
        ]);
    }
    public function save(Request $request){
        $data = $request->all();
        $preview=$request->preview;
        $ProposalID =$request->Quotation_ID;
        $adult = (int) $request->input('Adult', 0); // ใช้ค่าเริ่มต้นเป็น 0 ถ้าค่าไม่ถูกต้อง
        $children = (int) $request->input('Children', 0);
        $SpecialDiscount = $request->SpecialDiscount;
        $SpecialDiscountBath = $request->DiscountAmount;
        $userid = Auth::user()->id;
        $Proposal_ID = Quotation::where('Quotation_ID',$ProposalID)->first();
        if ($Proposal_ID) {
            $currentDate = Carbon::now();
            $ID = 'PD-';
            $formattedDate = Carbon::parse($currentDate);       // วันที่
            $month = $formattedDate->format('m'); // เดือน
            $year = $formattedDate->format('y');
            $lastRun = Quotation::latest()->first();
            $nextNumber = 1;
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
            $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $Quotation_ID = $ID.$year.$month.$newRunNumber;

        }else{
            $Quotation_ID =$ProposalID;
        }
        try {
            if ($preview == 1) {
                $datarequest = [
                    'Proposal_ID' => $data['Quotation_ID'] ?? null,
                    'IssueDate' => $data['IssueDate'] ?? null,
                    'Expiration' => $data['Expiration'] ?? null,
                    'Selectdata' => $data['selectdata'] ?? null,
                    'Data_ID' => $data['Guest'] ?? $data['Company'] ?? null,
                    'Adult' => $data['Adult'] ?? null,
                    'Children' => $data['Children'] ?? null,
                    'Mevent' => $data['Mevent'] ?? null,
                    'Mvat' => $data['Mvat'] ?? null,
                    'DiscountAmount' => $data['DiscountAmount'] ?? null,
                    'ProductIDmain' => $data['ProductIDmain'] ?? null,
                    'pax' => $data['pax'] ?? null,
                    'Quantitymain' => $data['Quantitymain'] ?? null,
                    'priceproductmain' => $data['priceproductmain'] ?? null,
                    'discountmain' => $data['discountmain'] ?? null,
                    'comment' => $data['comment'] ?? null,
                    'PaxToTalall' => $data['PaxToTalall'] ?? null,
                    'Checkin' => $data['Checkin'] ?? null,
                    'Checkout' => $data['Checkout'] ?? null,
                    'Day' => $data['Day'] ?? null,
                    'Night' => $data['Night'] ?? null,
                ];

                $Products = Arr::wrap($datarequest['ProductIDmain']);
                $quantities = $datarequest['Quantitymain'] ?? [];
                $discounts = $datarequest['discountmain'] ?? [];
                $priceUnits = $datarequest['priceproductmain'] ?? [];
                $productItems = [];
                $totaldiscount = [];
                foreach ($Products as $index => $productID) {
                    if (count($quantities) === count($priceUnits) && count($priceUnits) === count($discounts)) {
                        $totalPrices = []; // เปลี่ยนจากตัวแปรเดียวเป็น array เพื่อเก็บผลลัพธ์แต่ละรายการ
                        $discountedPrices = [];
                        $discountedPricestotal = [];
                        $totaldiscount = [];
                        // คำนวณราคาสำหรับแต่ละรายการ
                        for ($i = 0; $i < count($quantities); $i++) {
                            $quantity = intval($quantities[$i]);
                            $priceUnit = floatval(str_replace(',', '', $priceUnits[$i]));
                            $discount = floatval($discounts[$i]);

                            $totaldiscount0 = (($priceUnit * $discount)/100);
                            $totaldiscount[] = $totaldiscount0;

                            $totalPrice = ($quantity * $priceUnit);
                            $totalPrices[] = $totalPrice;

                            $discountedPrice = (($totalPrice * $discount )/ 100);
                            $discountedPrices[] = $priceUnit-$totaldiscount0;

                            $discountedPriceTotal = $totalPrice - $discountedPrice;
                            $discountedPricestotal[] = $discountedPriceTotal;
                        }
                    }
                    $items = master_product_item::where('Product_ID', $productID)->get();
                    $QuotationVat= $datarequest['Mvat'];
                    $Mvat = master_document::where('id',$QuotationVat)->where('status', '1')->where('Category','Mvat')->select('name_th','id')->first();
                    foreach ($items as $item) {
                        // ตรวจสอบและกำหนดค่า quantity และ discount
                        $quantity = isset($quantities[$index]) ? $quantities[$index] : 0;
                        $discount = isset($discounts[$index]) ? $discounts[$index] : 0;
                        $totalPrices = isset($totalPrices[$index]) ? $totalPrices[$index] : 0;
                        $discountedPrices = isset($discountedPrices[$index]) ? $discountedPrices[$index] : 0;
                        $discountedPricestotal = isset($discountedPricestotal[$index]) ? $discountedPricestotal[$index] : 0;
                        $totaldiscount = isset($totaldiscount[$index]) ? $totaldiscount[$index] : 0;
                        $productItems[] = [
                            'product' => $item,
                            'quantity' => $quantity,
                            'discount' => $discount,
                            'totalPrices'=>$totalPrices,
                            'discountedPrices'=>$discountedPrices,
                            'discountedPricestotal'=>$discountedPricestotal,
                            'totaldiscount'=>$totaldiscount,
                        ];
                    }
                }
                {//คำนวน
                    $totalAmount = 0;
                    $totalPrice = 0;
                    $subtotal = 0;
                    $beforeTax = 0;
                    $AddTax = 0;
                    $Nettotal =0;
                    $totalaverage=0;

                    $SpecialDistext = $datarequest['DiscountAmount'];
                    $SpecialDis = floatval($SpecialDistext);
                    $totalguest = 0;
                    $totalguest = $adult + $children;
                    $guest = $request->PaxToTalall;
                    if ($Mvat->id == 50) {
                        foreach ($productItems as $item) {
                            $totalPrice += $item['totalPrices'];
                            $totalAmount += $item['discountedPricestotal'];
                            $subtotal = $totalAmount-$SpecialDis;
                            $beforeTax = $subtotal/1.07;
                            $AddTax = $subtotal-$beforeTax;
                            $Nettotal = $subtotal;
                            $totalaverage =$Nettotal/$guest;

                        }
                    }
                    elseif ($Mvat->id == 51) {
                        foreach ($productItems as $item) {
                            $totalPrice += $item['totalPrices'];
                            $totalAmount += $item['discountedPricestotal'];
                            $subtotal = $totalAmount-$SpecialDis;
                            $Nettotal = $subtotal;
                            $totalaverage =$Nettotal/$guest;

                        }
                    }
                    elseif ($Mvat->id == 52) {
                        foreach ($productItems as $item) {
                            $totalPrice += $item['totalPrices'];
                            $totalAmount += $item['discountedPricestotal'];
                            $subtotal = $totalAmount-$SpecialDis;
                            $AddTax = $subtotal*7/100;
                            $Nettotal = $subtotal+$AddTax;
                            $totalaverage =$Nettotal/$guest;
                        }
                    }else
                    {
                        foreach ($productItems as $item) {
                            $totalPrice += $item['totalPrices'];
                            $totalAmount += $item['discountedPricestotal'];
                            $subtotal = $totalAmount-$SpecialDis;
                            $beforeTax = $subtotal/1.07;
                            $AddTax = $subtotal-$beforeTax;
                            $Nettotal = $subtotal;
                            $totalaverage =$Nettotal/$guest;
                        }
                    }
                    $pagecount = count($productItems);
                    $page = $pagecount/10;

                    $page_item = 1;
                    if ($page > 1.1 && $page < 2.1) {
                        $page_item += 1;

                    } elseif ($page > 1.1) {
                    $page_item = 1 + $page > 1.1 ? ceil($page) : 1;
                    }
                }
                {//QRCODE
                    $id = $datarequest['Proposal_ID'];
                    $protocol = $request->secure() ? 'https' : 'http';
                    $linkQR = $protocol . '://' . $request->getHost() . "/Quotation/Quotation/cover/document/PDF/$id?page_shop=" . $request->input('page_shop');
                    $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
                    $qrCodeBase64 = base64_encode($qrCodeImage);
                }
                $Proposal_ID = $datarequest['Proposal_ID'];
                $IssueDate = $datarequest['IssueDate'];
                $Expiration = $datarequest['Expiration'];
                $Selectdata = $datarequest['Selectdata'];
                $Data_ID = $datarequest['Data_ID'];
                $Adult = $datarequest['Adult'];
                $Children = $datarequest['Children'];
                $Mevent = $datarequest['Mevent'];
                $Mvat = $datarequest['Mvat'];
                $DiscountAmount = $datarequest['DiscountAmount'];
                $Checkin = $datarequest['Checkin'];
                $Checkout = $datarequest['Checkout'];
                $Day = $datarequest['Day'];
                $Night = $datarequest['Night'];
                $comment = $datarequest['comment'];
                $user = User::where('id',$userid)->select('id','name')->first();
                $fullName = null;
                $Contact_Name = null;
                $Contact_phone =null;
                $Contact_Email = null;
                if ($Selectdata == 'Guest') {
                    $Data = Guest::where('Profile_ID',$Data_ID)->first();
                    $prename = $Data->preface;
                    $First_name = $Data->First_name;
                    $Last_name = $Data->Last_name;
                    $Address = $Data->Address;
                    $Email = $Data->Email;
                    $Taxpayer_Identification = $Data->Identification_Number;
                    $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
                    $name = $prefix->name_th;
                    $fullName = $name.' '.$First_name.' '.$Last_name;
                    //-------------ที่อยู่
                    $CityID=$Data->City;
                    $amphuresID = $Data->Amphures;
                    $TambonID = $Data->Tambon;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    $Fax_number = '-';
                    $phone = phone_guest::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
                }else{
                    $Company = companys::where('Profile_ID',$Data_ID)->first();
                    $Company_type = $Company->Company_type;
                    $Compannyname = $Company->Company_Name;
                    $Address = $Company->Address;
                    $Email = $Company->Company_Email;
                    $Taxpayer_Identification = $Company->Taxpayer_Identification;
                    $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
                    if ($comtype) {
                        if ($comtype->name_th == "บริษัทจำกัด") {
                            $fullName = "บริษัท " . $Compannyname . " จำกัด";
                        } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                            $fullName = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                        } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                            $fullName = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                        }
                    }
                    $representative = representative::where('Company_ID',$Data_ID)->first();
                    $prename = $representative->prefix;
                    $Contact_Email = $representative->Email;
                    $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
                    $name = $prefix->name_th;
                    $Contact_Name = $representative->First_name.' '.$representative->Last_name;
                    $CityID=$Company->City;
                    $amphuresID = $Company->Amphures;
                    $TambonID = $Company->Tambon;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    $company_fax = company_fax::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
                    if ($company_fax) {
                        $Fax_number =  $company_fax->Fax_number;
                    }else{
                        $Fax_number = '-';
                    }
                    $phone = company_phone::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
                    $Contact_phone = representative_phone::where('Company_ID',$Data_ID)->where('Sequence','main')->first();
                }
                $eventformat = master_document::where('id',$Mevent)->select('name_th','id')->first();
                $template = master_template::query()->latest()->first();
                $CodeTemplate = $template->CodeTemplate;
                $sheet = master_document_sheet::select('topic','name_th','id','CodeTemplate')->get();
                $Reservation_show = $sheet->where('topic', 'Reservation')->where('CodeTemplate',$CodeTemplate)->first();
                $Paymentterms = $sheet->where('topic', 'Paymentterms')->where('CodeTemplate',$CodeTemplate)->first();
                $note = $sheet->where('topic', 'note')->where('CodeTemplate',$CodeTemplate)->first();
                $Cancellations = $sheet->where('topic', 'Cancellations')->where('CodeTemplate',$CodeTemplate)->first();
                $Complimentary = $sheet->where('topic', 'Complimentary')->where('CodeTemplate',$CodeTemplate)->first();
                $All_rights_reserved = $sheet->where('topic', 'All_rights_reserved')->where('CodeTemplate',$CodeTemplate)->first();
                $date = Carbon::now();
                $unit = master_unit::where('status',1)->get();
                $quantity = master_quantity::where('status',1)->get();
                $settingCompany = Master_company::orderBy('id', 'desc')->first();
                if ($Checkin) {
                    $checkin = Carbon::parse($Checkin)->format('d/m/Y');
                    $checkout = Carbon::parse($Checkout)->format('d/m/Y');
                }else{
                    $checkin = '-';
                    $checkout = '-';
                }
                $data = [
                    'settingCompany'=>$settingCompany,
                    'page_item'=>$page_item,
                    'page'=>$pagecount,
                    'Selectdata'=>$Selectdata,
                    'date'=>$date,
                    'fullName'=>$fullName,
                    'provinceNames'=>$provinceNames,
                    'Address'=>$Address,
                    'amphuresID'=>$amphuresID,
                    'TambonID'=>$TambonID,
                    'Email'=>$Email,
                    'phone'=>$phone,
                    'Fax_number'=>$Fax_number,
                    'Day'=>$Day,
                    'Night'=>$Night,
                    'Checkin'=>$checkin,
                    'Checkout'=>$checkout,
                    'eventformat'=>$eventformat,
                    'totalguest'=>$totalguest,
                    'Reservation_show'=>$Reservation_show,
                    'Paymentterms'=>$Paymentterms,
                    'note'=>$note,
                    'Cancellations'=>$Cancellations,
                    'Complimentary'=>$Complimentary,
                    'All_rights_reserved'=>$All_rights_reserved,
                    'Proposal_ID'=>$Proposal_ID,
                    'IssueDate'=>$IssueDate,
                    'Expiration'=>$Expiration,
                    'qrCodeBase64'=>$qrCodeBase64,
                    'user'=>$user,
                    'Taxpayer_Identification'=>$Taxpayer_Identification,
                    'Adult'=>$Adult,
                    'Children'=>$Children,
                    'totalAmount'=>$totalAmount,
                    'SpecialDis'=>$SpecialDis,
                    'subtotal'=>$subtotal,
                    'beforeTax'=>$beforeTax,
                    'Nettotal'=>$Nettotal,
                    'totalguest'=>$totalguest,
                    'guest'=>$guest,
                    'totalaverage'=>$totalaverage,
                    'AddTax'=>$AddTax,
                    'productItems'=>$productItems,
                    'unit'=>$unit,
                    'quantity'=>$quantity,
                    'Mvat'=>$Mvat,
                    'comment'=>$comment,
                    'Mevent'=>$Mevent,
                    'Contact_Name'=>$Contact_Name,
                    'Contact_phone'=>$Contact_phone,
                    'Contact_Email'=>$Contact_Email,
                ];
                $view= $template->name;
                $pdf = FacadePdf::loadView('quotationpdf.preview',$data);
                return $pdf->stream();
            }else{
                $datarequest = [
                    'Proposal_ID' => $data['Quotation_ID'] ?? null,
                    'IssueDate' => $data['IssueDate'] ?? null,
                    'Expiration' => $data['Expiration'] ?? null,
                    'Selectdata' => $data['selectdata'] ?? null,
                    'Data_ID' => $data['Guest'] ?? $data['Company'] ?? null,
                    'Adult' => $data['Adult'] ?? null,
                    'Children' => $data['Children'] ?? null,
                    'Mevent' => $data['Mevent'] ?? null,
                    'Mvat' => $data['Mvat'] ?? null,
                    'DiscountAmount' => $data['DiscountAmount'] ?? null,
                    'ProductIDmain' => $data['ProductIDmain'] ?? null,
                    'pax' => $data['pax'] ?? null,
                    'Quantitymain' => $data['Quantitymain'] ?? null,
                    'priceproductmain' => $data['priceproductmain'] ?? null,
                    'discountmain' => $data['discountmain'] ?? null,
                    'comment' => $data['comment'] ?? null,
                    'PaxToTalall' => $data['PaxToTalall'] ?? null,
                    'FreelancerMember' => $data['Freelancer_member'] ?? null,
                    'Checkin' => $data['Checkin'] ?? null,
                    'Checkout' => $data['Checkout'] ?? null,
                    'Day' => $data['Day'] ?? null,
                    'Night' => $data['Night'] ?? null,
                ];
                $quantities = $datarequest['Quantitymain'] ?? [];
                $discounts = $datarequest['discountmain'] ?? [];
                $priceUnits = $datarequest['priceproductmain'] ?? [];
                $discounts = array_map(function($value) {
                    return ($value !== null) ? $value : "0";
                }, $discounts);

                if (count($quantities) === count($priceUnits) && count($priceUnits) === count($discounts)) {
                    $totalPrices = []; // เปลี่ยนจากตัวแปรเดียวเป็น array เพื่อเก็บผลลัพธ์แต่ละรายการ
                    $discountedPrices = [];
                    $discountedPricestotal = [];
                    // คำนวณราคาสำหรับแต่ละรายการ
                    for ($i = 0; $i < count($quantities); $i++) {
                        $quantity = intval($quantities[$i]);
                        $priceUnit = floatval(str_replace(',', '', $priceUnits[$i]));
                        $discount = floatval($discounts[$i]);

                        $totalPrice = ($quantity * $priceUnit);
                        $totalPrices[] = $totalPrice;

                        $discountedPrice = (($totalPrice * $discount )/ 100);
                        $discountedPrices[] = $discountedPrice;

                        $discountedPriceTotal = $totalPrice - $discountedPrice;
                        $discountedPricestotal[] = $discountedPriceTotal;
                    }
                }
                foreach ($priceUnits as $key => $price) {
                    $priceUnits[$key] = str_replace(array(',', '.00'), '', $price);
                }
                $Products = $datarequest['ProductIDmain'];
                $pax=$datarequest['pax'];
                $productsArray = [];

                foreach ($Products as $index => $ProductID) {
                    $saveProduct = [
                        'Quotation_ID' => $datarequest['Proposal_ID'],
                        'Company_ID' => $datarequest['Data_ID'],
                        'Product_ID' => $ProductID,
                        'pax' => $pax[$index] ?? 0,
                        'Issue_date' => $datarequest['IssueDate'],
                        'discount' => $discounts[$index],
                        'priceproduct' => $priceUnits[$index],
                        'netpriceproduct' => $discountedPricestotal[$index],
                        'totaldiscount' => $discountedPrices[$index],
                        'ExpirationDate' => $datarequest['Expiration'],
                        'freelanceraiffiliate' => $datarequest['FreelancerMember'],
                        'Quantity' => $quantities[$index],
                        'Document_issuer' => $userid,
                    ];

                    $productsArray[] = $saveProduct;
                }
                {
                    $Quotation_ID = $datarequest['Proposal_ID'];
                    $IssueDate = $datarequest['IssueDate'];
                    $Expiration = $datarequest['Expiration'];
                    $Selectdata = $datarequest['Selectdata'];
                    $Data_ID = $datarequest['Data_ID'];
                    $Adult = $datarequest['Adult'];
                    $Children = $datarequest['Children'];
                    $Mevent = $datarequest['Mevent'];
                    $Mvat = $datarequest['Mvat'];
                    $DiscountAmount = $datarequest['DiscountAmount'];
                    $Checkin = $datarequest['Checkin'];
                    $Checkout = $datarequest['Checkout'];
                    $Day = $datarequest['Day'];
                    $Night = $datarequest['Night'];
                    $Head = 'รายการ';
                    if ($productsArray) {
                        $products['products'] =$productsArray;
                        $productsArray = $products['products']; // ใช้ array ที่คุณมีอยู่แล้ว
                        $productData = [];

                        foreach ($productsArray as $product) {
                            $productID = $product['Product_ID'];

                            // ค้นหาข้อมูลในฐานข้อมูลจาก Product_ID
                            $productDetails = master_product_item::LeftJoin('master_units', 'master_product_items.unit', '=', 'master_units.id')
                                ->where('master_product_items.Product_ID', $productID)
                                ->select('master_product_items.*', 'master_units.name_th as unit_name')
                                ->first();

                            $ProductName = $productDetails->name_en;
                            $unitName = $productDetails->unit_name;

                            if ($productDetails) {
                                $productData[] = [
                                    'Product_ID' => $productID,
                                    'Quantity' => $product['Quantity'],
                                    'netpriceproduct' => $product['netpriceproduct'],
                                    'Product_Name' => $ProductName,
                                    'Product_Unit' => $unitName, // หรือระบุฟิลด์ที่ต้องการจาก $productDetails
                                ];
                            }
                        }
                    }
                    $formattedProductData = [];

                    foreach ($productData as $product) {
                        $formattedPrice = number_format($product['netpriceproduct']).' '.'บาท';
                        $formattedProductData[] = 'Description : ' . $product['Product_Name'] . ' , ' . 'Quantity : ' . $product['Quantity'] . ' ' . $product['Product_Unit'] . ' , ' . 'Price Product : ' . $formattedPrice;
                    }

                    if ($Quotation_ID) {
                        $QuotationID = 'Proposal ID : '.$Quotation_ID;
                    }
                    if ($IssueDate) {
                        $Issue_Date = 'Issue Date : '.$IssueDate;
                    }
                    if ($Expiration) {
                        $Expiration_Date = 'Expiration Date : '.$Expiration;
                    }

                    $fullName = null;
                    $Contact_Name = null;
                    if ($Selectdata == 'Guest') {
                        $Data = Guest::where('Profile_ID',$Data_ID)->first();
                        $prename = $Data->preface;
                        $First_name = $Data->First_name;
                        $Last_name = $Data->Last_name;
                        $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
                        $name = $prefix->name_th;
                        $fullName = $name.$First_name.' '.$Last_name;
                    }else{
                        $Company = companys::where('Profile_ID',$Data_ID)->first();
                        $Company_type = $Company->Company_type;
                        $Compannyname = $Company->Company_Name;
                        $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
                        if ($comtype) {
                            if ($comtype->name_th == "บริษัทจำกัด") {
                                $fullName = "บริษัท " . $Compannyname . " จำกัด";
                            } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                                $fullName = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                            } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                                $fullName = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                            }
                        }
                        $representative = representative::where('Company_ID',$Data_ID)->first();
                        $prename = $representative->prefix;
                        $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
                        $name = $prefix->name_th;
                        $Contact_Name = 'ตัวแทน : '.$name.$representative->First_name.' '.$representative->Last_name;
                    }
                    $nameevent = null;
                    if ($Mevent) {
                        $Mevent = master_document::where('id',$Mevent)->where('status', '1')->where('Category','Mevent')->first();
                        $nameevent = 'ประเภท : '.$Mevent->name_th;
                    }
                    $namevat = null;
                    if ($Mvat) {
                        $Mvat = master_document::where('id',$Mvat)->where('status', '1')->where('Category','Mvat')->first();
                        $namevat = 'ประเภท VAT : '.$Mvat->name_th;
                    }
                    $Time =null;
                    if ($Checkin) {
                        $checkin = Carbon::parse($Checkin)->format('d/m/Y');
                        $checkout = Carbon::parse($Checkout)->format('d/m/Y');
                        $Time = 'วันเข้าที่พัก : '.$checkin.' '.'วันออกที่พัก : '.$checkout.' '.'จำนวน : '.$Day.' วัน '.' '.$Night.' คืน ';
                    }

                    $datacompany = '';

                    $variables = [$QuotationID, $Issue_Date, $Expiration_Date, $fullName, $Contact_Name,$Time,$nameevent,$namevat,$Head];

                    // แปลง array ของ $formattedProductData เป็น string เดียวที่มีรายการทั้งหมด
                    $formattedProductDataString = implode(' + ', $formattedProductData);

                    // รวม $formattedProductDataString เข้าไปใน $variables
                    $variables[] = $formattedProductDataString;

                    foreach ($variables as $variable) {
                        if (!empty($variable)) {
                            if (!empty($datacompany)) {
                                $datacompany .= ' + ';
                            }
                            $datacompany .= $variable;
                        }
                    }
                    $userid = Auth::user()->id;
                    $save = new log_company();
                    $save->Created_by = $userid;
                    $save->Company_ID = $Quotation_ID;
                    $save->type = 'Create';
                    $save->Category = 'Create :: Proposal';
                    $save->content =$datacompany;
                    $save->save();
                }
                $save = new Quotation();
                $save->Quotation_ID = $Quotation_ID;
                $save->DummyNo = $Quotation_ID;
                $save->Company_ID = $datarequest['Data_ID'];
                $save->company_contact = $datarequest['Data_ID'];
                $save->checkin = $request->Checkin;
                $save->checkout = $request->Checkout;
                $save->TotalPax = $request->PaxToTalall;
                $save->day = $request->Day;
                $save->night = $request->Night;
                $save->adult = $request->Adult;
                $save->children = $request->Children;
                $save->ComRateCode = $request->Company_Rate_Code;
                $save->freelanceraiffiliate = $request->Freelancer_member;
                $save->commissionratecode = $request->Company_Commission_Rate_Code;
                $save->eventformat = $request->Mevent;
                $save->vat_type = $request->Mvat;
                $save->type_Proposal = $Selectdata;
                $save->issue_date = $request->IssueDate;
                $save->ComRateCode = $request->Company_Discount;
                $save->Expirationdate = $request->Expiration;
                $save->Operated_by = $userid;
                $save->Refler_ID=$Quotation_ID;
                $save->comment = $request->comment;
                if ($SpecialDiscount == 0 && $SpecialDiscountBath == 0) {
                    $save->SpecialDiscount = $SpecialDiscount;
                    $save->SpecialDiscountBath = $SpecialDiscountBath;
                    $save->status_document = 1;
                    $save->Confirm_by = 'Auto';
                    $save->save();
                }else {
                    $save->SpecialDiscount = $SpecialDiscount;
                    $save->SpecialDiscountBath = $SpecialDiscountBath;
                    $save->status_document = 2;
                    $save->Confirm_by = '-';
                    $save->save();
                }
                if ($Products !== null) {
                    foreach ($Products as $index => $ProductID) {
                        $saveProduct = new document_quotation();
                        $saveProduct->Quotation_ID = $Quotation_ID;
                        $saveProduct->Company_ID = $datarequest['Data_ID'];
                        $saveProduct->Product_ID = $ProductID;
                        $paxValue = $pax[$index] ?? 0;
                        $saveProduct->pax = $paxValue;
                        $saveProduct->Issue_date = $request->IssueDate;
                        $saveProduct->discount =$discounts[$index];
                        $saveProduct->priceproduct =$priceUnits[$index];
                        $saveProduct->netpriceproduct =$discountedPricestotal[$index];
                        $saveProduct->totaldiscount =$discountedPrices[$index];
                        $saveProduct->ExpirationDate = $request->Expiration;
                        $saveProduct->freelanceraiffiliate = $request->Freelancer_member;
                        $saveProduct->Quantity = $quantities[$index];
                        $saveProduct->Document_issuer = $userid;
                        $saveProduct->save();
                    }
                    $currentDateTime = Carbon::now();
                    $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
                    $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS

                    // Optionally, you can format the date and time as per your requirement
                    $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
                    $formattedTime = $currentDateTime->format('H:i:s');
                    $savePDF = new log();
                    $savePDF->Quotation_ID = $Quotation_ID;
                    $savePDF->QuotationType = 'Proposal';
                    $savePDF->Approve_date = $formattedDate;
                    $savePDF->Approve_time = $formattedTime;
                    $savePDF->save();
                    {
                        //-----------------------PDF---------------------------
                        $datarequestPDF = [
                            'Proposal_ID' => $data['Quotation_ID'] ?? null,
                            'IssueDate' => $data['IssueDate'] ?? null,
                            'Expiration' => $data['Expiration'] ?? null,
                            'Selectdata' => $data['selectdata'] ?? null,
                            'Data_ID' => $data['Guest'] ?? $data['Company'] ?? null,
                            'Adult' => $data['Adult'] ?? null,
                            'Children' => $data['Children'] ?? null,
                            'Mevent' => $data['Mevent'] ?? null,
                            'Mvat' => $data['Mvat'] ?? null,
                            'DiscountAmount' => $data['DiscountAmount'] ?? null,
                            'ProductIDmain' => $data['ProductIDmain'] ?? null,
                            'pax' => $data['pax'] ?? null,
                            'Quantitymain' => $data['Quantitymain'] ?? null,
                            'priceproductmain' => $data['priceproductmain'] ?? null,
                            'discountmain' => $data['discountmain'] ?? null,
                            'comment' => $data['comment'] ?? null,
                            'PaxToTalall' => $data['PaxToTalall'] ?? null,
                            'Checkin' => $data['Checkin'] ?? null,
                            'Checkout' => $data['Checkout'] ?? null,
                            'Day' => $data['Day'] ?? null,
                            'Night' => $data['Night'] ?? null,
                        ];

                        $Products = Arr::wrap($datarequestPDF['ProductIDmain']);
                        $quantities = $datarequestPDF['Quantitymain'] ?? [];
                        $discounts = $datarequestPDF['discountmain'] ?? [];
                        $priceUnits = $datarequestPDF['priceproductmain'] ?? [];
                        $productItems = [];
                        $totaldiscount = [];
                        foreach ($Products as $index => $productID) {
                            if (count($quantities) === count($priceUnits) && count($priceUnits) === count($discounts)) {
                                $totalPrices = []; // เปลี่ยนจากตัวแปรเดียวเป็น array เพื่อเก็บผลลัพธ์แต่ละรายการ
                                $discountedPrices = [];
                                $discountedPricestotal = [];
                                $totaldiscount = [];
                                // คำนวณราคาสำหรับแต่ละรายการ
                                for ($i = 0; $i < count($quantities); $i++) {
                                    $quantity = intval($quantities[$i]);
                                    $priceUnit = floatval(str_replace(',', '', $priceUnits[$i]));
                                    $discount = floatval($discounts[$i]);

                                    $totaldiscount0 = (($priceUnit * $discount)/100);
                                    $totaldiscount[] = $totaldiscount0;

                                    $totalPrice = ($quantity * $priceUnit);
                                    $totalPrices[] = $totalPrice;

                                    $discountedPrice = (($totalPrice * $discount )/ 100);
                                    $discountedPrices[] = $priceUnit-$totaldiscount0;

                                    $discountedPriceTotal = $totalPrice - $discountedPrice;
                                    $discountedPricestotal[] = $discountedPriceTotal;
                                }
                            }
                            $items = master_product_item::where('Product_ID', $productID)->get();
                            $QuotationVat= $datarequestPDF['Mvat'];
                            $Mvat = master_document::where('id',$QuotationVat)->where('status', '1')->where('Category','Mvat')->select('name_th','id')->first();
                            foreach ($items as $item) {
                                // ตรวจสอบและกำหนดค่า quantity และ discount
                                $quantity = isset($quantities[$index]) ? $quantities[$index] : 0;
                                $discount = isset($discounts[$index]) ? $discounts[$index] : 0;
                                $totalPrices = isset($totalPrices[$index]) ? $totalPrices[$index] : 0;
                                $discountedPrices = isset($discountedPrices[$index]) ? $discountedPrices[$index] : 0;
                                $discountedPricestotal = isset($discountedPricestotal[$index]) ? $discountedPricestotal[$index] : 0;
                                $totaldiscount = isset($totaldiscount[$index]) ? $totaldiscount[$index] : 0;
                                $productItems[] = [
                                    'product' => $item,
                                    'quantity' => $quantity,
                                    'discount' => $discount,
                                    'totalPrices'=>$totalPrices,
                                    'discountedPrices'=>$discountedPrices,
                                    'discountedPricestotal'=>$discountedPricestotal,
                                    'totaldiscount'=>$totaldiscount,
                                ];
                            }
                        }
                        {//คำนวน
                            $totalAmount = 0;
                            $totalPrice = 0;
                            $subtotal = 0;
                            $beforeTax = 0;
                            $AddTax = 0;
                            $Nettotal =0;
                            $totalaverage=0;

                            $SpecialDistext = $datarequestPDF['DiscountAmount'];
                            $SpecialDis = floatval($SpecialDistext);
                            $totalguest = 0;
                            $totalguest = $adult + $children;
                            $guest = $request->PaxToTalall;
                            if ($Mvat->id == 50) {
                                foreach ($productItems as $item) {
                                    $totalPrice += $item['totalPrices'];
                                    $totalAmount += $item['discountedPricestotal'];
                                    $subtotal = $totalAmount-$SpecialDis;
                                    $beforeTax = $subtotal/1.07;
                                    $AddTax = $subtotal-$beforeTax;
                                    $Nettotal = $subtotal;
                                    $totalaverage =$Nettotal/$guest;

                                }
                            }
                            elseif ($Mvat->id == 51) {
                                foreach ($productItems as $item) {
                                    $totalPrice += $item['totalPrices'];
                                    $totalAmount += $item['discountedPricestotal'];
                                    $subtotal = $totalAmount-$SpecialDis;
                                    $Nettotal = $subtotal;
                                    $totalaverage =$Nettotal/$guest;

                                }
                            }
                            elseif ($Mvat->id == 52) {
                                foreach ($productItems as $item) {
                                    $totalPrice += $item['totalPrices'];
                                    $totalAmount += $item['discountedPricestotal'];
                                    $subtotal = $totalAmount-$SpecialDis;
                                    $AddTax = $subtotal*7/100;
                                    $Nettotal = $subtotal+$AddTax;
                                    $totalaverage =$Nettotal/$guest;
                                }
                            }else
                            {
                                foreach ($productItems as $item) {
                                    $totalPrice += $item['totalPrices'];
                                    $totalAmount += $item['discountedPricestotal'];
                                    $subtotal = $totalAmount-$SpecialDis;
                                    $beforeTax = $subtotal/1.07;
                                    $AddTax = $subtotal-$beforeTax;
                                    $Nettotal = $subtotal;
                                    $totalaverage =$Nettotal/$guest;
                                }
                            }
                            $pagecount = count($productItems);
                            $page = $pagecount/10;

                            $page_item = 1;
                            if ($page > 1.1 && $page < 2.1) {
                                $page_item += 1;

                            } elseif ($page > 1.1) {
                            $page_item = 1 + $page > 1.1 ? ceil($page) : 1;
                            }
                        }
                        {//QRCODE
                            $id = $datarequestPDF['Proposal_ID'];
                            $protocol = $request->secure() ? 'https' : 'http';
                            $linkQR = $protocol . '://' . $request->getHost() . "/Quotation/Quotation/cover/document/PDF/$id?page_shop=" . $request->input('page_shop');
                            $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
                            $qrCodeBase64 = base64_encode($qrCodeImage);
                        }
                        $Proposal_ID = $datarequestPDF['Proposal_ID'];
                        $IssueDate = $datarequestPDF['IssueDate'];
                        $Expiration = $datarequestPDF['Expiration'];
                        $Selectdata = $datarequestPDF['Selectdata'];
                        $Data_ID = $datarequestPDF['Data_ID'];
                        $Adult = $datarequestPDF['Adult'];
                        $Children = $datarequestPDF['Children'];
                        $Mevent = $datarequestPDF['Mevent'];
                        $Mvat = $datarequestPDF['Mvat'];
                        $DiscountAmount = $datarequestPDF['DiscountAmount'];
                        $Checkin = $datarequestPDF['Checkin'];
                        $Checkout = $datarequestPDF['Checkout'];
                        $Day = $datarequestPDF['Day'];
                        $Night = $datarequestPDF['Night'];
                        $comment = $datarequestPDF['comment'];
                        $user = User::where('id',$userid)->select('id','name')->first();
                        $fullName = null;
                        $Contact_Name = null;
                        $Contact_phone =null;
                        $Contact_Email = null;
                        if ($Selectdata == 'Guest') {
                            $Data = Guest::where('Profile_ID',$Data_ID)->first();
                            $prename = $Data->preface;
                            $First_name = $Data->First_name;
                            $Last_name = $Data->Last_name;
                            $Address = $Data->Address;
                            $Email = $Data->Email;
                            $Taxpayer_Identification = $Data->Identification_Number;
                            $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
                            $name = $prefix->name_th;
                            $fullName = $name.' '.$First_name.' '.$Last_name;
                            //-------------ที่อยู่
                            $CityID=$Data->City;
                            $amphuresID = $Data->Amphures;
                            $TambonID = $Data->Tambon;
                            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                            $Fax_number = '-';
                            $phone = phone_guest::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
                        }else{
                            $Company = companys::where('Profile_ID',$Data_ID)->first();
                            $Company_type = $Company->Company_type;
                            $Compannyname = $Company->Company_Name;
                            $Address = $Company->Address;
                            $Email = $Company->Company_Email;
                            $Taxpayer_Identification = $Company->Taxpayer_Identification;
                            $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
                            if ($comtype) {
                                if ($comtype->name_th == "บริษัทจำกัด") {
                                    $fullName = "บริษัท " . $Compannyname . " จำกัด";
                                } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                                    $fullName = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                                } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                                    $fullName = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                                }
                            }
                            $representative = representative::where('Company_ID',$Data_ID)->first();
                            $prename = $representative->prefix;
                            $Contact_Email = $representative->Email;
                            $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
                            $name = $prefix->name_th;
                            $Contact_Name = $representative->First_name.' '.$representative->Last_name;
                            $CityID=$Company->City;
                            $amphuresID = $Company->Amphures;
                            $TambonID = $Company->Tambon;
                            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                            $company_fax = company_fax::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
                            if ($company_fax) {
                                $Fax_number =  $company_fax->Fax_number;
                            }else{
                                $Fax_number = '-';
                            }
                            $phone = company_phone::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
                            $Contact_phone = representative_phone::where('Company_ID',$Data_ID)->where('Sequence','main')->first();
                        }
                        $eventformat = master_document::where('id',$Mevent)->select('name_th','id')->first();
                        $template = master_template::query()->latest()->first();
                        $CodeTemplate = $template->CodeTemplate;
                        $sheet = master_document_sheet::select('topic','name_th','id','CodeTemplate')->get();
                        $Reservation_show = $sheet->where('topic', 'Reservation')->where('CodeTemplate',$CodeTemplate)->first();
                        $Paymentterms = $sheet->where('topic', 'Paymentterms')->where('CodeTemplate',$CodeTemplate)->first();
                        $note = $sheet->where('topic', 'note')->where('CodeTemplate',$CodeTemplate)->first();
                        $Cancellations = $sheet->where('topic', 'Cancellations')->where('CodeTemplate',$CodeTemplate)->first();
                        $Complimentary = $sheet->where('topic', 'Complimentary')->where('CodeTemplate',$CodeTemplate)->first();
                        $All_rights_reserved = $sheet->where('topic', 'All_rights_reserved')->where('CodeTemplate',$CodeTemplate)->first();
                        $date = Carbon::now();
                        $unit = master_unit::where('status',1)->get();
                        $quantity = master_quantity::where('status',1)->get();
                        $settingCompany = Master_company::orderBy('id', 'desc')->first();
                        if ($Checkin) {
                            $checkin = Carbon::parse($Checkin)->format('d/m/Y');
                            $checkout = Carbon::parse($Checkout)->format('d/m/Y');
                        }else{
                            $checkin = '-';
                            $checkout = '-';
                        }
                        $data = [
                            'settingCompany'=>$settingCompany,
                            'page_item'=>$page_item,
                            'page'=>$pagecount,
                            'Selectdata'=>$Selectdata,
                            'date'=>$date,
                            'fullName'=>$fullName,
                            'provinceNames'=>$provinceNames,
                            'Address'=>$Address,
                            'amphuresID'=>$amphuresID,
                            'TambonID'=>$TambonID,
                            'Email'=>$Email,
                            'phone'=>$phone,
                            'Fax_number'=>$Fax_number,
                            'Day'=>$Day,
                            'Night'=>$Night,
                            'Checkin'=>$checkin,
                            'Checkout'=>$checkout,
                            'eventformat'=>$eventformat,
                            'totalguest'=>$totalguest,
                            'Reservation_show'=>$Reservation_show,
                            'Paymentterms'=>$Paymentterms,
                            'note'=>$note,
                            'Cancellations'=>$Cancellations,
                            'Complimentary'=>$Complimentary,
                            'All_rights_reserved'=>$All_rights_reserved,
                            'Proposal_ID'=>$Proposal_ID,
                            'IssueDate'=>$IssueDate,
                            'Expiration'=>$Expiration,
                            'qrCodeBase64'=>$qrCodeBase64,
                            'user'=>$user,
                            'Taxpayer_Identification'=>$Taxpayer_Identification,
                            'Adult'=>$Adult,
                            'Children'=>$Children,
                            'totalAmount'=>$totalAmount,
                            'SpecialDis'=>$SpecialDis,
                            'subtotal'=>$subtotal,
                            'beforeTax'=>$beforeTax,
                            'Nettotal'=>$Nettotal,
                            'totalguest'=>$totalguest,
                            'guest'=>$guest,
                            'totalaverage'=>$totalaverage,
                            'AddTax'=>$AddTax,
                            'productItems'=>$productItems,
                            'unit'=>$unit,
                            'quantity'=>$quantity,
                            'Mvat'=>$Mvat,
                            'comment'=>$comment,
                            'Mevent'=>$Mevent,
                            'Contact_Name'=>$Contact_Name,
                            'Contact_phone'=>$Contact_phone,
                            'Contact_Email'=>$Contact_Email,
                        ];
                        $view= $template->name;
                        $pdf = FacadePdf::loadView('quotationpdf.'.$view,$data);
                        // บันทึกไฟล์ PDF
                        $path = 'Log_PDF/proposal/';
                        $pdf->save($path . $Quotation_ID . '.pdf');

                        $Quotation = Quotation::where('Quotation_ID',$Quotation_ID)->first();
                        $Quotation->AddTax = $AddTax;
                        $Quotation->Nettotal = $Nettotal;
                        $Quotation->total = $Nettotal;
                        $Quotation->save();
                        $Auto = $Quotation->Confirm_by;
                        $id = $Quotation->id;
                        if ($Auto = 'Auto') {
                            return redirect()->route('Proposal.email', ['id' => $id])->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
                        }else{
                            return redirect()->route('Proposal.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
                        }
                    }
                }else{
                    $delete = Quotation::find($id);
                    $delete->delete();
                    return redirect()->route('Proposal.index')->with('success', 'ใบเสนอราคายังไม่ถูกสร้าง');
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    //----------------------------ส่งอีเมล์---------------------
    public function email($id){
        $quotation = Quotation::where('id',$id)->first();
        $comid = $quotation->Company_ID;
        $Quotation_ID= $quotation->Quotation_ID;
        $type_Proposal = $quotation->type_Proposal;
        $comtypefullname = null;
        if ($type_Proposal == 'Guest') {
            $companys = Guest::where('Profile_ID',$comid)->first();
            $emailCom = $companys->Email;
            $namefirst = $companys->First_name;
            $namelast = $companys->Last_name;
            $name = $namefirst.' '.$namelast;
        }else{
            $companys = companys::where('Profile_ID',$comid)->first();
            $emailCom = $companys->Company_Email;
            $contact = $companys->Profile_ID;
            $Contact_name = representative::where('Company_ID',$contact)->where('status',1)->first();
            $namefirst = $Contact_name->First_name;
            $namelast = $Contact_name->Last_name;
            $name = $namefirst.' '.$namelast;
            $Company_typeID=$companys->Company_type;
            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
            if ($comtype->name_th =="บริษัทจำกัด") {
                $comtypefullname = "Company : "." บริษัท ". $companys->Company_Name . " จำกัด";
            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                $comtypefullname = "Company : "." บริษัท ". $companys->Company_Name . " จำกัด (มหาชน)";
            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                $comtypefullname = "Company : "." ห้างหุ้นส่วนจำกัด ". $companys->Company_Name ;
            }else {
                $comtypefullname = $companys->Company_Name;
            }
        }

        $Checkin = $quotation->checkin;
        $Checkout = $quotation->checkout;
        if ($Checkin) {
            $checkin = Carbon::parse($Checkin)->format('d/m/Y').' '.'-'.'';
            $checkout = Carbon::parse($Checkout)->format('d/m/Y');
        }else{
            $checkin = 'No Check in date';
            $checkout = ' ';
        }
        $day =$quotation->day;
        $night= $quotation->night;
        if ($day == null) {
            $day = ' ';
            $night = ' ';
        }else{
            $day = '( '.$day.' วัน';
            $night =$night.' คืน'.' )';
        }

        return view('quotation_email.index',compact('emailCom','Quotation_ID','name','comtypefullname','checkin','checkout','night','day',
                        'quotation','type_Proposal'));
    }

    public function sendemail(Request $request,$id){
        try {
            $file = $request->all();

            $quotation = Quotation::where('id',$id)->first();
            $QuotationID = $quotation->Quotation_ID;
            $path = 'Log_PDF/proposal/';
            $pdf = $path.$QuotationID;
            $pdfPath = $path.$QuotationID.'.pdf';
            $comid = $quotation->Company_ID;
            $Quotation_ID= $quotation->Quotation_ID;
            $companys = companys::where('Profile_ID',$comid)->first();
            $emailCom = $companys->Company_Email;
            $contact = $quotation->company_contact;
            $Contact_name = representative::where('id',$contact)->where('status',1)->first();
            $emailCon = $Contact_name->Email;
            $Title = $request->tital;
            $detail = $request->detail;
            $comment = $request->Comment;
            $email = $request->email;
            $promotiondata = master_promotion::where('status', 1)->select('name')->get();
            $promotion_path = 'promotion/';
            $promotions = [];
            foreach ($promotiondata as $promo) {
                $promotions[] = $promotion_path . $promo->name;
            }
            $fileUploads = $request->file('files'); // ใช้ 'files' ถ้าฟิลด์ในฟอร์มเป็น 'files[]'

            // ตรวจสอบว่ามีไฟล์ถูกอัปโหลดหรือไม่
            if ($fileUploads) {
                $filePaths = [];
                foreach ($fileUploads as $file) {
                    $filename = $file->getClientOriginalName();
                    $file->move(public_path($path), $filename);
                    $filePaths[] = public_path($path . $filename);
                }
            } else {
                // หากไม่มีไฟล์ที่อัปโหลด ให้กำหนด $filePaths เป็นอาร์เรย์ว่าง
                $filePaths = [];
            }
            $Data = [
                'title' => $Title,
                'detail' => $detail,
                'comment' => $comment,
                'email' => $email,
                'pdfPath'=>$pdfPath,
                'pdf'=>$pdf,
            ];

            $customEmail = new QuotationEmail($Data,$Title,$pdfPath,$filePaths,$promotions);
            Mail::to($emailCon)->send($customEmail);
            $userid = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = $Quotation_ID;
            $save->type = 'Send Email';
            $save->Category = 'Send Email :: Proposal';
            $save->content = 'Send Email Document Proposal ID : '.$Quotation_ID;
            $save->save();
            return redirect()->route('Proposal.index')->with('success', 'บันทึกข้อมูลและส่งอีเมลเรียบร้อยแล้ว');
        } catch (\Throwable $th) {
            return redirect()->route('Proposal.index')->with('error', 'เกิดข้อผิดพลาดในการส่งอีเมล์');
        }

    }
    //-----------------------------รายการ---------------------
    public function addProduct($Quotation_ID, Request $request) {
        $value = $request->input('value');
        if ($value == 'Room_Type') {

            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Room_Type')->select('master_product_items.*','master_units.name_th as unit_name')->get();

        }elseif ($value == 'Banquet') {
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Banquet')->select('master_product_items.*','master_units.name_th as unit_name')->get();

        }elseif ($value == 'Meals') {
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Meals')->select('master_product_items.*','master_units.name_th as unit_name')->get();

        }elseif ($value == 'Entertainment') {
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Entertainment')->select('master_product_items.*','master_units.name_th as unit_name')->get();

        }
        elseif ($value == 'all'){
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.type', 'asc')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->select('master_product_items.*','master_units.name_th as unit_name')->get();
        }
        return response()->json([
            'products' => $products,

        ]);
    }


    public function addProducttable($Quotation_ID, Request $request) {

        $value = $request->input('value');
        if ($value == 'Room_Type') {

            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Room_Type')->select('master_product_items.*','master_units.name_th as unit_name')->get();

        }elseif ($value == 'Banquet') {
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Banquet')->select('master_product_items.*','master_units.name_th as unit_name')->get();

        }elseif ($value == 'Meals') {
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Meals')->select('master_product_items.*','master_units.name_th as unit_name')->get();

        }elseif ($value == 'Entertainment') {
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Entertainment')->select('master_product_items.*','master_units.name_th as unit_name')->get();

        }
        elseif ($value == 'all'){
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.type', 'asc')
            ->where('master_product_items.status',1)->select('master_product_items.*','master_units.name_th as unit_name')->get();

        }
        return response()->json([
            'products' => $products,

        ]);
    }

    public function addProductselect($Quotation_ID, Request $request) {
        $value = $request->input('value');
        $products = master_product_item::leftJoin('master_units', 'master_product_items.unit', '=', 'master_units.id')
        ->orderBy('master_product_items.type', 'asc')
        ->where('master_product_items.status', 1)
        ->where('master_product_items.id', $value)
        ->select('master_product_items.*', 'master_units.name_th as unit_name')
        ->get();

        return response()->json([
            'products' => $products,

        ]);
    }
    public function addProducttableselect($Quotation_ID, Request $request) {
        $value = $request->input('value');
        $products = master_product_item::leftJoin('master_units', 'master_product_items.unit', '=', 'master_units.id')
        ->orderBy('master_product_items.type', 'asc')
        ->where('master_product_items.status', 1)
        ->where('master_product_items.id', $value)
        ->select('master_product_items.*', 'master_units.name_th as unit_name')
        ->get();

        return response()->json([
            'products' => $products,

        ]);
    }
    public function addProducttablemain($Quotation_ID, Request $request) {
        $value = $request->input('value');
        $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')
        ->where('master_product_items.status',1)->select('master_product_items.*','master_units.name_th as unit_name','master_quantities.name_th as quantity_name')->get();
        return response()->json([
            'products' => $products,

        ]);
    }
    public function addProducttablecreatemain($Quotation_ID, Request $request) {
        $value = $request->input('value');
        $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')
        ->where('master_product_items.status',1)->select('master_product_items.*','master_units.name_th as unit_name','master_quantities.name_th as quantity_name')->get();
        return response()->json([
            'products' => $products,

        ]);
    }
}
