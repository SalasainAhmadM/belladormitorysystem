<?php
session_start();
define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bella');
$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>
<script language="javascript" type="text/javascript">
  function f2() {
    window.close();
  }
  function f3() {
    window.print();
  }
</script>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Student Information</title>
  <link href="style.css" rel="stylesheet" type="text/css" />
  <link href="bella.css" rel="stylesheet" type="text/css">
</head>

<body>
  <table width="100%" border="0">
    <?php
    $ret = mysqli_query($con, "SELECT * FROM registration where id = '" . $_GET['id'] . "'");
    while ($row = mysqli_fetch_array($ret)) {
      ?>
      <tr>
        <td colspan="2" align="center" class="font1">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="font1">&nbsp;</td>
      </tr>

      <tr>
        <td colspan="2" class="font"><?php echo ucfirst($row['firstName']); ?>   <?php echo ucfirst($row['lastName']); ?>'S
          <span class="font1"> information &raquo;</span>
        </td>
      </tr>
      <tr>
        <td colspan="2" class="font">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <div align="right">Reg Date : <span class="comb-value"><?php echo $row['postingDate']; ?></span></div>
        </td>
      </tr>
      <tr>
        <td colspan="2" class="heading" style="color: red;">Room Related Info &raquo; </td>
      </tr>
      <tr>
        <td colspan="2" class="font1">
          <table width="100%" border="0">
            <tr>
              <td width="32%" valign="top" class="heading">Room no : </td>

              <td class="comb-value1"><span class="comb-value"><?php echo $row['roomno']; ?></span></td>
            </tr>
            <tr>
              <td width="22%" valign="top" class="heading">Bed : </td>

              <td class="comb-value1"><span class="comb-value"><?php echo $row['seater']; ?></span></td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading">Fees PM : </td>
              <td class="comb-value1"><?php echo $fpm = $row['feespm']; ?></td>
            </tr>
            <tr>
              <td width="12%" valign="top" class="heading">Food Status:</td>
              <td class="comb-value1"><?php echo ($row['foodstatus'] == 0) ? "Without Food" : "With Food (₱ 2,000.00)"; ?>
              </td>
            </tr>
            <tr>
              <td width="12%" valign="top" class="heading">Water Status:</td>
              <td class="comb-value1">
                <?php echo ($row['waterstatus'] == 0) ? "Without Water" : "With Water (₱ 1,000.00)"; ?>
              </td>
            </tr>
            <tr>
              <td width="12%" valign="top" class="heading">Electric Status:</td>
              <td class="comb-value1">
                <?php echo ($row['electricstatus'] == 0) ? "Without Electric" : "With Electric (₱ 1,500.00)"; ?>
              </td>
            </tr>
            <tr>
              <td width="12%" valign="top" class="heading">Internet Status:</td>
              <td class="comb-value1">
                <?php echo ($row['internetstatus'] == 0) ? "Without Internet" : "With Internet (₱ 1,000.00)"; ?>
              </td>
            </tr>
            <tr>
              <td width="12%" valign="top" class="heading">Staying From:</td>
              <td class="comb-value1"><?php echo $row['stayfrom']; ?></td>
            </tr>
            <tr>
              <td width="12%" valign="top" class="heading">Duration:</td>
              <td class="comb-value1"><?php echo $dr = $row['duration']; ?> Months</td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading"><b>Monthly Fee:</b></td>
              <td class="comb-value1">
                <?php
                $feespm = $row['feespm'];
                // Define additional costs based on status
                $foodCost = ($row['foodstatus'] == 1) ? 2000 : 0;
                $waterCost = ($row['waterstatus'] == 1) ? 1000 : 0;
                $electricCost = ($row['electricstatus'] == 1) ? 1500 : 0;
                $internetCost = ($row['internetstatus'] == 1) ? 1000 : 0;

                // Calculate total monthly cost before any payments
                $monthlyFee = $feespm + $foodCost + $waterCost + $electricCost + $internetCost;

                // Get current month and year
                $currentMonth = date("F Y"); // Example: "January 2025"
                $regno = $row['regno'];

                // Fetch the amount paid for the current month
                $query = "SELECT SUM(amount_paid) AS totalPaid FROM monthly_payments WHERE regno = ? AND month = ?";
                $stmt = $con->prepare($query);
                $stmt->bind_param("is", $regno, $currentMonth);
                $stmt->execute();
                $result = $stmt->get_result();
                $amountPaid = 0;

                if ($result->num_rows > 0) {
                  $rowPayment = $result->fetch_assoc();
                  $amountPaid = $rowPayment['totalPaid'];
                }

                // Calculate balance
                $balance = $monthlyFee - $amountPaid;

                // Display Monthly Fee and Balance
                echo "₱ " . number_format($monthlyFee, 2, '.', ',') . " - Balance: ₱ " . number_format($balance, 2, '.', ',');

                // If fully paid, append " - Paid For This Month"
                if ($balance <= 0) {
                  echo " <b style='color: green;'>- Paid For This Month</b>";
                }
                ?>
              </td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading"><b>Total Fee:</b></td>
              <td class="comb-value1">
                <?php
                // Multiply the monthly fee by the number of months
                $totalFee = $dr * $monthlyFee;

                // Fetch total amount paid by the user
                $regno = $row['regno'];
                $query = "SELECT SUM(amount_paid) AS totalPaid FROM monthly_payments WHERE regno = ?";
                $stmt = $con->prepare($query);
                $stmt->bind_param("i", $regno);
                $stmt->execute();
                $result = $stmt->get_result();
                $totalPaid = 0;

                if ($result->num_rows > 0) {
                  $rowPayment = $result->fetch_assoc();
                  $totalPaid = $rowPayment['totalPaid'];
                }

                // Calculate remaining balance
                $remainingBalance = $totalFee - $totalPaid;

                // Format and display the Total Fee and Balance
                echo "₱ " . number_format($totalFee, 2, '.', ',') . " - Paid: ₱ " . number_format($totalPaid, 2, '.', ',') . " - Balance: ₱ " . number_format($remainingBalance, 2, '.', ',');
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="left" class="heading" style="color: red;">Personal Info &raquo; </td>
            </tr>
            <tr>
              <td width="12%" valign="top" class="heading">Course: </td>
              <td class="comb-value1"><?php echo $row['course']; ?></td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading">Reg no: </td>
              <td class="comb-value1"><?php echo $row['regno']; ?></td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading">First Name: </td>
              <td class="comb-value1"><?php echo $row['firstName']; ?></td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading">Middle name: </td>
              <td class="comb-value1"><?php echo $row['middleName']; ?></td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading">Last: </td>
              <td class="comb-value1"><?php echo $row['lastName']; ?></td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading">Gender: </td>
              <td class="comb-value1"><?php echo $row['gender']; ?></td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading">Contact No: </td>
              <td class="comb-value1"><?php echo $row['contactno']; ?></td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading">Email id: </td>
              <td class="comb-value1"><?php echo $row['emailid']; ?></td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading">Emergency Contact: </td>
              <td class="comb-value1"><?php echo $row['egycontactno']; ?></td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading">Guardian Name: </td>
              <td class="comb-value1"><?php echo $row['guardianName']; ?></td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading">Guardian Relation: </td>
              <td class="comb-value1"><?php echo $row['guardianRelation']; ?></td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading">Guardian Contact: </td>
              <td class="comb-value1"><?php echo $row['guardianContactno']; ?></td>
            </tr>
            <tr>
              <td colspan="2" class="heading" style="color: red;">Correspondence Address &raquo; </td>
            </tr>
            <tr>
              <td width="12%" valign="top" class="heading">Address: </td>
              <td class="comb-value1"><?php echo $row['corresAddress']; ?></td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading">City: </td>
              <td class="comb-value1"><?php echo $row['corresCIty']; ?></td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading">Province: </td>
              <td class="comb-value1"><?php echo $row['corresState']; ?></td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading">Pincode: </td>
              <td class="comb-value1"><?php echo $row['corresPincode']; ?></td>
            </tr>
            <tr>
              <td colspan="2" class="heading" style="color: red;">Permanent Address &raquo; </td>
            </tr>
            <tr>
              <td width="12%" valign="top" class="heading">Address: </td>
              <td class="comb-value1"><?php echo $row['pmntAddress']; ?></td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading">City: </td>
              <td class="comb-value1"><?php echo $row['pmntCity']; ?></td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading">State: </td>
              <td class="comb-value1"><?php echo $row['pmnatetState']; ?></td>
            </tr>

            <tr>
              <td width="12%" valign="top" class="heading">Pincode: </td>
              <td class="comb-value1"><?php echo $row['pmntPincode']; ?></td>
            </tr>
            <tr>
              <td width="12%" valign="top" class="heading">State: </td>
              <td class="comb-value1"><?php echo $row['pmnatetState']; ?></td>
            </tr>
          <?php } ?>



        </table>
      </td>
    </tr>


  </table>
  </td>
  </tr>
  </table>
  </td>
  </tr>




  </table>
  </td>
  </tr>


  <tr>
    <td colspan="2" align="right">
      <form id="form1" name="form1" method="post" action="">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="14%">&nbsp;</td>
            <td width="35%" class="comb-value"><label>
                <input name="Submit" type="submit" class="txtbox4" value="Prints this Document "
                  onClick="return f3();" />
              </label></td>
            <td width="3%">&nbsp;</td>
            <td width="26%"><label>
                <input name="Submit2" type="submit" class="txtbox4" value="Close this document "
                  onClick="return f2();" />
              </label></td>
            <td width="8%">&nbsp;</td>
            <td width="14%">&nbsp;</td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  </table>
</body>

</html>