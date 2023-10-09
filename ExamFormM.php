<?php
error_reporting(0);


if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class ExamFormM extends CI_Model
{
	public function __construct()
	{

		parent::__construct();
		$this->load->library('Mylib');
	}


	// public function GetDeptSectionList($department_id, $academic_session)
	// {
	// 	$query = "SELECT section_name, section_id, semester FROM section WHERE department = $department_id and academic_session_id = $academic_session";

	// 	$result = $this->db->query($query);
	// 	return $result->result_array();
	// }

	// public function GetYearList()
	// {
	// 	$query = "SELECT sem_id, year FROM semester WHERE active = 1 ORDER BY year ";

	// 	$result = $this->db->query($query);
	// 	return $result->result_array();
	// }

	// public function GetYear()
	// {
	// 	$query = "SELECT sem_id, year FROM semester WHERE active = 1 ORDER BY year ";

	// 	$result = $this->db->query($query);
	// 	return $result->result_array();
	// }

	public function checkResultStatus($department, $department_type, $semester)
	{
		$query = "SELECT result_live from module where department = $department and department_type = '$department_type' and semester = $semester";
		$result = $this->db->query($query);
		// print_r($query);
		// die();
		return $result->result_array();
	}
	public function get_specialization($department)
	{
		$query = "SELECT * FROM mtech_specialization WHERE active = 1 and department_id = $department order by specialization_name";

		$result = $this->db->query($query);
		return $result->result_array();
	}
	public function GetStudentList($semester, $department_id, $academic_session, $specialization)
	{
		$query = "SELECT distinct student_college_info_new.computer_code, temp_student_data.enrollment_number, temp_student_data.student_name, temp_student_data.student_mobile, student_college_info_new.home_dept,student_college_info_new.semester FROM student_college_info_new, temp_student_data WHERE  student_college_info_new.academic_session = $academic_session and student_college_info_new.computer_code = temp_student_data.computer_code and student_college_info_new.home_dept = $department_id and student_college_info_new.semester = $semester and temp_student_data.specialization = $specialization and student_college_info_new.specialization=$specialization ORDER BY temp_student_data.enrollment_number";

		// print_r($query);
		// die();
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function StudentFormForward($data)
	{
		return $this->db->insert_batch('exam_form_forward', $data);
	}

	public function GetSubmittedExamForm($semester, $department_id, $academic_session)
	{
		$semester = md5($semester . '402');
		$department_id = md5($department_id . '402');
		$academic_session = md5($academic_session . '402');
		$query = "SELECT computer_code FROM exam_form_forward WHERE semester = '$semester' and academic_session = '$academic_session' and department_id = '$department_id' ";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetStudentDetails($computer_code)
	{
		$query = "SELECT temp_student_data.enrollment_number, temp_student_data.student_name, temp_student_data.father_name, department.name, temp_student_data.student_gender, temp_student_data.add_permanent, temp_student_data.district_permanent, temp_student_data.state_permanent, temp_student_data.pincode_permanent, temp_student_data.student_mobile, temp_student_data.academic_session FROM temp_student_data, department WHERE temp_student_data.computer_code = $computer_code and department.id = temp_student_data.study_dept";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetExamFormStatus($computer_code, $department_id, $academic_session)
	{
		$computer_code = md5($computer_code . '402');
		$student_session = md5($student_session . '402');
		$department_id = md5($department_id . '402');
		$academic_session = md5($academic_session . '402');
		$query = "SELECT enrollment_no FROM exam_form_forward WHERE academic_session = '$academic_session' and department_id = '$department_id' and computer_code = '$computer_code' ";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetDepartment()
	{
		$query = "SELECT id, name FROM department WHERE core_dept = 1 and active = 1 ORDER BY name";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetSemester()
	{
		$query = "SELECT sem_id, year FROM semester WHERE active = 1";
		$result = $this->db->query($query);
		return $result->result_array();
	}
	public function GetAllSemester()
	{
		$query = "SELECT sem_id FROM semester";
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetYearSemester($year)
	{
		$query = "SELECT sem_id FROM semester WHERE year=$year and active = 1";
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetSubjects($department, $semester, $academic_session)
	{
		$query = "SELECT DISTINCT(subject.university_subject_code), subject.subject_name, subject.type FROM subject, section, class_subject WHERE section.department = $department AND section.semester = $semester AND section.academic_session_id = $academic_session AND section.section_id = class_subject.section_id AND class_subject.college_subject_code = subject.clg_subject_code ORDER BY subject.university_subject_code";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetRegularSubject($computer_code, $semester, $department, $academic_session)
	{
		$query = "SELECT distinct student_college_info_new.batch_id,subject_batch.subject_name, subject_batch.university_sub_code, subject_new.type FROM subject_new,subject_batch, student_college_info_new WHERE student_college_info_new.computer_code = $computer_code and student_college_info_new.semester = $semester and student_college_info_new.home_dept = $department and student_college_info_new.academic_session = $academic_session and student_college_info_new.batch_id = subject_batch.batch_id and subject_batch.university_sub_code = subject_new.university_sub_code and subject_batch.clg_sub_code = subject_new.clg_sub_code order by subject_batch.university_sub_code ";
		// print_r($query);
		// die();
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetATKTSubjects($computer_code)
	{
		$query = "SELECT subject.university_subject_code, student_backlog_record.clg_sub_code, student_backlog_record.sub_type, student_backlog_record.atkt_sem, subject.subject_name FROM student_backlog_record, subject WHERE student_backlog_record.computer_code=$computer_code AND student_backlog_record.atkt_status=1 AND subject.clg_subject_code = student_backlog_record.clg_sub_code ORDER BY student_backlog_record.university_sub_code";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetYearList()
	{
		$query = "SELECT sem_id,year FROM semester WHERE active = 1 ORDER BY year DESC";
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetStudentSemester($student_section_id, $department_id, $academic_session)
	{
		$query = "SELECT semester FROM section WHERE section_id = $student_section_id and department = $department_id and academic_session_id = $academic_session";
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetYear($semester)
	{
		$query = "SELECT year FROM semester WHERE sem_id = $semester ";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetExamFormDetails($academic_session, $semester, $course)
	{
		$query = "SELECT * FROM exam_form_details WHERE academic_session = $academic_session and semester = $semester and course = '$course'";
		// print_r($query);
		// die();
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function SubmitExamFormDetails($FormForwardDate, $FormFeeLastDate, $FormFee, $PortalFees, $FormForwardLastDateWithLateFee, $FormAfterLastDate, $FormClosingDate, $FormLateFee, $AfterExamLateFee, $academic_session, $semester, $course)
	{
		$query = "INSERT INTO exam_form_details(`form_forward_date`, `payment_last_date`, `late_fee_last_date`,`late_fee_after_last_date`,`form_closing_date` ,`payment`,`portal_fees`, `late_fee_charge`,`late_fees_after_exam`, `semester`, `academic_session`, `course`) VALUES ('$FormForwardDate', '$FormFeeLastDate', '$FormForwardLastDateWithLateFee','$FormAfterLastDate','$FormClosingDate', $FormFee,$PortalFees, $FormLateFee,$AfterExamLateFee, $semester, $academic_session, '$course')";

		$result = $this->db->query($query);
		return 1;
	}

	public function UpdateExamFormDetails($FormForwardDate, $FormFeeLastDate, $FormFee, $PortalFees, $FormForwardLastDateWithLateFee, $FormAfterLastDate, $FormClosingDate, $FormLateFee, $AfterExamLateFee, $academic_session, $semester, $course)
	{
		$query = "UPDATE exam_form_details SET `form_forward_date` = '$FormForwardDate', `payment_last_date` = '$FormFeeLastDate', `late_fee_last_date` = '$FormForwardLastDateWithLateFee',`late_fee_after_last_date`='$FormAfterLastDate', `form_closing_date`= '$FormClosingDate',`payment` = $FormFee, `portal_fees`=$PortalFees ,`late_fee_charge` = $FormLateFee,`late_fees_after_exam`=$AfterExamLateFee WHERE `academic_session` = $academic_session and `semester` = $semester and `course`='$course'";

		$result = $this->db->query($query);
		return;
	}

	public function SubmitTimeTableDetails($dept, $sem, $university_sub_code, $clg_sub_code, $type, $exam_date, $start_time, $end_time, $academic_session)
	{
		$query = "INSERT INTO exam_time_table(`dept`, `sem`,`university_sub_code`,`clg_sub_code`, `type`, `exam_date`, `start_time`, `end_time`, `academic_session`) VALUES ($dept, $sem, '$university_sub_code', '$clg_sub_code' ,'$type', '$exam_date', '$start_time', '$end_time', $academic_session)";
		// print_r($query);
		// die();
		$result = $this->db->query($query);
		return;
	}

	public function GetTimeTableDetails($dept, $sem, $academic_session)
	{
		$query = "SELECT * FROM exam_time_table WHERE dept = $dept AND sem = $sem AND academic_session = $academic_session ";
		$result = $this->db->query($query);
		// print_r($query);
		// die();
		return $result->result_array();
	}
	public function UpdateTimeTableDetails($dept, $sem, $university_sub_code, $clg_sub_code, $type, $exam_date, $start_time, $end_time, $academic_session)
	{
		$query = "UPDATE exam_time_table SET exam_date = '$exam_date', start_time = '$start_time', end_time = '$end_time' WHERE dept = $dept AND sem = $sem AND academic_session = $academic_session AND university_sub_code = '$university_sub_code' AND clg_sub_code= '$clg_sub_code' AND type = '$type'";
		// print_r($query);
		// die();

		$result = $this->db->query($query);
		return;
	}

	public function GetTransactionDetails($computer_code, $academic_session)
	{
		$query = "SELECT * FROM txn_details WHERE computer_code = $computer_code AND academic_session = $academic_session ORDER BY id DESC";
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetTransactionStatus($department_id, $academic_session, $semester)
	{
		$query = "SELECT computer_code FROM txn_details WHERE academic_session = $academic_session AND study_department_id = $department_id AND semester = $semester AND status_to_show = 'Success'";
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetExamSubjects($department, $semester, $academic_session)
	{
		$query = "SELECT DISTINCT(subject_batch.university_sub_code), subject_batch.subject_name, subject_new.type, subject_batch.clg_sub_code FROM subject_batch,subject_new WHERE subject_batch.department = $department AND subject_batch.semester = $semester AND subject_batch.academic_session_id = $academic_session AND subject_batch.subject_name = subject_new.subject_name AND subject_new.clg_sub_code = subject_batch.clg_sub_code ORDER BY subject_batch.university_sub_code";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetExamDepartment($dept_id)
	{
		$query = "SELECT name FROM department WHERE core_dept = 1 and active = 1 AND id=$dept_id";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function PrintTimeTableBySem($dept, $sem, $academic_session)
	{
		$query = "SELECT exam_time_table.university_sub_code, exam_time_table.type, exam_time_table.exam_date, exam_time_table.start_time, exam_time_table.end_time, subject_new.subject_name FROM exam_time_table,subject_new WHERE exam_time_table.dept = $dept AND exam_time_table.sem = $sem AND exam_time_table.academic_session = $academic_session AND  exam_time_table.university_sub_code=subject_new.university_sub_code AND exam_time_table.clg_sub_code=subject_new.clg_sub_code  ORDER BY exam_time_table.university_sub_code";
		// print_r($query); die(); 
		$result = $this->db->query($query);

		return $result->result_array();
	}

	public function PrintTimeTableByDate($academic_session, $date)
	{
		$query = "SELECT exam_time_table.*, subject_new.subject_name, department.name FROM exam_time_table, subject_new, department WHERE exam_time_table.academic_session = $academic_session AND subject_new.clg_sub_code = exam_time_table.clg_sub_code AND exam_time_table.exam_date = '$date' AND department.id = exam_time_table.dept AND  exam_time_table.university_sub_code=subject_new.university_sub_code ORDER BY exam_time_table.university_sub_code";
		$result = $this->db->query($query);
		return $result->result_array();
	}
	public function GetExamFormReceiptDetails($computer_code, $academic_session, $student_section_id)
	{
		$query = "SELECT * FROM txn_details WHERE computer_code = $computer_code and student_section_id = $student_section_id and academic_session = $academic_session ORDER BY id desc ";
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetTxnByComputerCode($academic_session, $computer_code)
	{
		$query = "SELECT * FROM txn_details WHERE computer_code = $computer_code and academic_session = $academic_session ORDER BY id desc ";
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetTxnByEnrollmentNo($academic_session, $enrollment_number)
	{
		$query = "SELECT * FROM txn_details WHERE enrollment = '$enrollment_number' and academic_session = $academic_session ORDER BY id desc ";
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetTxnBTxnNumber($academic_session, $txn_number)
	{
		$query = "SELECT * FROM txn_details WHERE txnid Like '%$txn_number%' and academic_session = $academic_session ORDER BY id desc ";
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function UpdateStudentTxnDetails($student_id, $status_to_show, $msg, $msg1)
	{
		$query = "UPDATE txn_details SET `status_to_show` = '$status_to_show', `msg` = '$msg', `msg1` = '$msg1' WHERE `id` = $student_id ";
		$result = $this->db->query($query);
		return;
	}

	public function GetExaminationDetails($computer_code, $academic_session, $home_dept)
	{
		$query = "SELECT DISTINCT subject_batch.subject_name, exam_time_table.university_sub_code, exam_time_table.type, exam_time_table.exam_date,  exam_time_table.academic_session,exam_time_table.dept FROM  exam_time_table ,student_college_info_new, subject_batch WHERE  student_college_info_new.computer_code=$computer_code AND student_college_info_new.home_dept=exam_time_table.dept AND student_college_info_new.semester=exam_time_table.sem AND subject_batch.university_sub_code=exam_time_table.university_sub_code AND subject_batch.type=exam_time_table.type  AND subject_batch.clg_sub_code=exam_time_table.clg_sub_code AND exam_time_table.academic_session = $academic_session AND exam_time_table.dept = $home_dept AND  student_college_info_new.academic_session=$academic_session AND student_college_info_new.batch_id=subject_batch.batch_id";
		// print_r($query);
		// die();
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetStudentInfo($computer_code, $academic_session)
	{
		$query = "SELECT distinct temp_student_data.enrollment_number,  temp_student_data.student_name, student_college_info_new.semester,student_college_info_new.home_dept,student_college_info_new.course FROM student_college_info_new, temp_student_data WHERE temp_student_data.computer_code = $computer_code and  student_college_info_new.academic_session = $academic_session and student_college_info_new.computer_code =$computer_code and temp_student_data.enrollment_number = student_college_info_new.enrollment_no and temp_student_data.computer_code = student_college_info_new.computer_code ";
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetStudentInfoMTech($computer_code, $academic_session)
	{
		$query = "SELECT distinct temp_student_data.enrollment_number,  temp_student_data.student_name, student_college_info_new.semester,student_college_info_new.home_dept,student_college_info_new.course,mtech_specialization.specialization_name FROM student_college_info_new, temp_student_data, mtech_specialization WHERE temp_student_data.computer_code = $computer_code and  student_college_info_new.academic_session = $academic_session and student_college_info_new.computer_code =$computer_code and temp_student_data.enrollment_number = student_college_info_new.enrollment_no and temp_student_data.computer_code = student_college_info_new.computer_code and mtech_specialization.specialization_id = student_college_info_new.specialization and mtech_specialization.department_id = student_college_info_new.home_dept and mtech_specialization.department_type = student_college_info_new.course";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetDepartmentName($department)
	{
		$query = "SELECT name FROM department WHERE id = $department and core_dept = 1 and active = 1";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetExamSession($academic_session)
	{
		$query = "SELECT academic_session FROM academic_session WHERE academic_session_id = $academic_session";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetStudentMarks($computer_code, $enrollment_number, $department, $semester, $academic_session)
	{
		$table_name1 = 'student_main_result_relative_' . $academic_session;
		$table_name2 = 'student_result_relative_' . $academic_session;
		$table_name3 = 'subject_new';
		// $table_name3 = 'result_subject_'.$academic_session;

		$query = "SELECT t2.university_subject_code, t3.subject_name, t2.subject_type, t2.total_credit, t2.earn_credit, t2.grade, t2.result, t2.grace_marks, t1.SGPA, t1.CGPA, t2.college_subject_code FROM $table_name1 as t1, $table_name2 as t2, $table_name3 as t3 WHERE t1.computer_code = $computer_code and t1.enrollment_no = '$enrollment_number' and t1.department_id = $department and t1.semester = $semester and t1.academic_session = $academic_session and t2.computer_code = t1.computer_code and t2.enrollment_no = t1.enrollment_no and t2.academic_session = $academic_session and t3.clg_sub_code = t2.college_subject_code and t3.university_sub_code = t2.university_subject_code and t3.academic_session=$academic_session ORDER BY t2.subject_type DESC,t2.semester ASC, t2.university_subject_code ASC";
		// $query = "SELECT t2.university_subject_code, t3.subject_name, t2.subject_type, t2.total_credit, t2.earn_credit, t2.grade, t2.result, t2.grace_marks, t1.SGPA, t1.CGPA, t2.college_subject_code FROM $table_name1 as t1, $table_name2 as t2, $table_name3 as t3 WHERE t1.computer_code = $computer_code and t1.enrollment_no = '$enrollment_number' and t1.department_id = $department and t1.semester = $semester and t1.academic_session = $academic_session and t2.computer_code = t1.computer_code and t2.enrollment_no = t1.enrollment_no and t2.department_id = t1.department_id and t2.semester = t1.semester and t2.academic_session = $academic_session and t3.clg_sub_code = t2.college_subject_code and t3.university_sub_code = t2.university_subject_code and t3.academic_session=$academic_session ORDER BY t2.subject_type DESC, t2.university_subject_code ASC ";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetStudentATKTInfo($computer_code)
	{
		$query = "SELECT temp_student_data.enrollment_number, temp_student_data.student_name, student_college_info_new.study_dept FROM student_college_info_new, temp_student_data WHERE student_college_info_new.computer_code = $computer_code and temp_student_data.enrollment_number = student_college_info_new.enrollment_no and temp_student_data.computer_code = student_college_info_new.computer_code ";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetStudentSectionId($computer_code, $academic_session)
	{
		$query = "SELECT section_id FROM student_data WHERE computer_code = $computer_code and academic_session = $academic_session ";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetATKTCGPA($computer_code, $enrollment, $department, $ATKT_semester, $academic_session)
	{
		$table_name = 'student_atkt_main_result_' . $academic_session;

		$query = "SELECT CGPA FROM $table_name WHERE computer_code = $computer_code and enrollment_no = '$enrollment' and department_id = $department and semester = $ATKT_semester and academic_session = $academic_session ";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetStudentNewSgpaCgpa($computer_code, $enrollment, $department, $ATKT_semester, $academic_session)
	{
		$table_name1 = 'student_atkt_main_result_' . $academic_session;


		$query = "SELECT t1.SGPA, t1.CGPA FROM $table_name1 as t1  WHERE t1.computer_code = $computer_code and t1.enrollment_no = '$enrollment' and t1.department_id = $department and t1.semester = $ATKT_semester and t1.academic_session = $academic_session  ";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetATKTSemSubjectDetails($computer_code, $enrollment, $department, $ATKT_semester, $academic_session)
	{
		$table_name = 'student_atkt_result_' . $academic_session;

		$query = "SELECT student_subject_result.university_subject_code, student_subject_result.college_subject_code, student_subject_result.subject_type, student_subject_result.total_credit, student_subject_result.earn_credit, student_subject_result.grade_point, subject.subject_name FROM student_subject_result, subject WHERE student_subject_result.computer_code = $computer_code and student_subject_result.enrollment_no = '$enrollment' and student_subject_result.study_department_id = $department and student_subject_result.semester = $ATKT_semester and subject.clg_subject_code = student_subject_result.college_subject_code and subject.university_subject_code = student_subject_result.university_subject_code UNION SELECT t1.university_subject_code, t1.college_subject_code, t1.subject_type, t1.total_credit, t1.earn_credit, t1.grade_point, subject.subject_name FROM $table_name as t1, subject WHERE computer_code = $computer_code and enrollment_no = '$enrollment' and department_id = $department and atkt_semester = $ATKT_semester and academic_session = $academic_session and subject.clg_subject_code = t1.college_subject_code and subject.university_subject_code = t1.university_subject_code ORDER BY subject_type DESC, university_subject_code";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetATKTSubjectResult($computer_code, $enrollment, $department, $ATKT_semester, $academic_session)
	{
		$table_name = 'student_atkt_result_' . $academic_session;

		$query = "SELECT college_subject_code, university_subject_code, subject_type, earn_credit, grade, total_credit, result FROM $table_name WHERE computer_code = $computer_code and enrollment_no = '$enrollment' and department_id = $department and atkt_semester = $ATKT_semester and academic_session = $academic_session ";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetATKTSemester($computer_code, $academic_session)
	{
		$table_name = 'student_atkt_main_result_' . $academic_session;

		$query = "SELECT semester FROM $table_name WHERE computer_code = $computer_code and academic_session = $academic_session ";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function InsertStudentPhotographDetails($computer_code, $enrollment_number, $file_location)
	{
		$query = "INSERT INTO student_photograph_record (`computer_code`, `enrollment_no`, `path`, `status`) VALUES($computer_code, '$enrollment_number', '$file_location', 1)";

		$result = $this->db->query($query);
		return;

	}

	public function GetPhotographStatus($computer_code, $enrollment_number, $status)
	{
		$query = "SELECT id FROM student_photograph_record WHERE computer_code = $computer_code and enrollment_no = '$enrollment_number' and status = $status";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function UpdateStudentPhotographDetails($computer_code, $enrollment_number, $file_location)
	{
		$query = "UPDATE student_photograph_record SET `path` = '$file_location', `status` = 1 WHERE computer_code = $computer_code and enrollment_no = '$enrollment_number'";

		$result = $this->db->query($query);
		return;
	}

	public function Check_Pic($computer_code)
	{
		$query = "SELECT id, enrollment_no, `path` FROM student_photograph_record WHERE computer_code = $computer_code";

		$result = $this->db->query($query);
		return $result->result_array();
	}
	public function Check_Pic_for_admit_card($uid, $computer_code)
	{
		$query = "SELECT counselling_documents_name.uid, counselling_documents_name.document_name FROM counselling_documents_name, temp_student_data WHERE  temp_student_data.computer_code = $computer_code AND counselling_documents_name.uid=temp_student_data.uid and document_name LIKE '%-%' ";
		// print_r($query); die();
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetCBGSIDs($cbgs_section_id)
	{
		$query = "SELECT id FROM cbgs_section_id WHERE section_id = $cbgs_section_id ";
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetMCSubjectCode($MC_Subject_Code)
	{
		$query = "SELECT id FROM mandatory_subjects WHERE clg_subject_code = '$MC_Subject_Code' ";
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function GetBatch($computer_code, $semester, $academic_session)
	{
		$query = "SELECT batch_id from student_college_info_new where computer_code = $computer_code and semester = $semester and academic_session = $academic_session' ";
		$result = $this->db->query($query);
		// print_r($query);die();
		return $result->result_array();
	}
	public function student_admission_exec_list($faculty_computer_code)
	{
		// ******* academic_session is hard coded because of some reasons. **********************
		//************** Please update academic session every year **********************
		$where = array('counselling_verification.entry_by_computer_code' => $faculty_computer_code, 'temp_student_data.academic_session' => '2022-2023');
		// **********************************************************************************
		$this->db->select('temp_student_data.*, counselling_verification.*');
		$this->db->from('counselling_verification');
		$this->db->join('temp_student_data', 'temp_student_data.uid =  counselling_verification.uid');
		$this->db->where($where);
		$this->db->order_by('counselling_verification.uid', 'desc');

		$query = $this->db->get();
		return $query->result();
	}
	public function get_subject($academic_session, $department, $sem)
	{
		$query = "SELECT DISTINCT  subject_name, university_sub_code FROM subject_batch where academic_session_id = $academic_session and department =$department and semester= $sem and specialization =0 and type = 'T'";
		// print_r($query);
		//  die();
		$data = $this->db->query($query);
		return $data->result_array();
	}
	public function get_student($sem, $department, $academic_session, $specialization)
	{
		$query = "SELECT distinct student_college_info_new.enrollment_no , student_college_info_new.student_name,student_data.detained, temp_student_data.father_name ,counselling_documents_name.document_name FROM student_college_info_new, temp_student_data, student_data, counselling_documents_name where student_college_info_new.computer_code= temp_student_data.computer_code and  counselling_documents_name.uid=temp_student_data.uid and student_college_info_new.semester =$sem and student_college_info_new.home_dept =$department and student_college_info_new.academic_session = $academic_session and student_college_info_new.specialization=$specialization and student_data.computer_code = student_college_info_new.computer_code and student_data.academic_session = student_college_info_new.academic_session and counselling_documents_name.document_name like '%-%' order by student_college_info_new.enrollment_no";
		//  print_r($query);
		//  die();
		$result = $this->db->query($query);
		return $result->result_array();
	}
	public function get_department_name($dept_id)
	{
		$query = "SELECT  name FROM department WHERE id = $dept_id";
		// print_r($query);
		// die();
		$data = $this->db->query($query);
		return $data->result_array();
	}
	public function get_subject_mtech($academic_session, $department, $sem, $Specialization)
	{
		$query = "SELECT DISTINCT  subject_name,university_sub_code FROM subject_batch where academic_session_id = $academic_session and department =$department and semester= $sem and specialization =$Specialization and type = 'T' ";
		// print_r($query);
		//  die();
		$data = $this->db->query($query);
		return $data->result_array();
	}
	public function get_student_mtech($sem, $department, $academic_session, $specialization)
	{
		$query = "SELECT distinct student_college_info_new.enrollment_no , student_college_info_new.student_name,student_data.detained, temp_student_data.father_name ,counselling_documents_name.document_name FROM student_college_info_new, temp_student_data, student_data, counselling_documents_name where student_college_info_new.computer_code= temp_student_data.computer_code and  counselling_documents_name.uid=temp_student_data.uid and student_college_info_new.semester =$sem and student_college_info_new.home_dept =$department and student_college_info_new.academic_session = $academic_session and student_college_info_new.specialization=$specialization and student_data.computer_code = student_college_info_new.computer_code and student_data.academic_session = student_college_info_new.academic_session and counselling_documents_name.document_name like '%-%' order by student_college_info_new.enrollment_no";
		//  print_r($query);
		//  die();
		$result = $this->db->query($query);
		return $result->result_array();
	}
	public function Get_Year($department, $department_type)
	{
		$query = "SELECT semester FROM module WHERE department = $department and department_type = '$department_type' and exam_module = 1 order by semester asc";
		// print_r($query);die();
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function Get_YearM($department, $department_type, $specialization)
	{
		$query = "SELECT semester FROM module WHERE department = $department and department_type = '$department_type' and specialization = $specialization and exam_module = 1 order by semester asc";
		// print_r($query);die();
		$result = $this->db->query($query);
		return $result->result_array();
	}
	public function get_department($specialization)
	{
		$query = "SELECT department_id FROM mtech_specialization WHERE specialization_id = $specialization and active = 1";
		// print_r($query);die();
		$result = $this->db->query($query);
		return $result->result_array();
	}
	public function get_elective_subject($department,  $academic_session, $specialization)
	{
		// $query = "SELECT DISTINCT subject_name , university_sub_code,elective FROM subject_new where department = $department and semester = $sem and  academic_session = $academic_session and specialization= $specialization and type = 'T' ";
		
		 $query = "SELECT DISTINCT subject_new.subject_name , subject_new.university_sub_code,subject_new.elective, subject_batch.batch_id FROM subject_new,subject_batch,student_college_info_new WHERE student_college_info_new.home_dept=$department and student_college_info_new.specialization=$specialization and  student_college_info_new.academic_session=$academic_session and  subject_new.clg_sub_code = subject_batch.clg_sub_code  and subject_batch.batch_id=student_college_info_new.batch_id and subject_new.type= 'T' and subject_new.elective != 0 ";
		// print_r($query);
		// die();
		$result = $this->db->query($query);
		return $result->result_array();
	}
	public function UpdateStudentData($student_name, $student_father_name, $student_address, $computer_code)
	{
		$query = "UPDATE  temp_student_data SET student_name = '$student_name', father_name = '$student_father_name', add_permanent = '$student_address' WHERE computer_code = $computer_code";
		$result = $this->db->query($query);
		return;
	}
}