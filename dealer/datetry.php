<?php
$today = date('Y-m-d');
?>
<form>
  <label for="start_date">Start Date:</label>
  <input type="date" id="start_date" name="start_date" min="<?php echo $today; ?>" class="form-control">

  <label for="end_date">End Date:</label>
  <input type="date" id="end_date" name="end_date" min="<?php echo $today; ?>" class="form-control">
</form>

<script>
  document.getElementById("start_date").addEventListener("change", function () {
    const startDate = new Date(this.value);
    
    if (isNaN(startDate)) return;

    // Set max date as 10 days after the selected start date
    const maxDate = new Date(startDate);
    maxDate.setDate(maxDate.getDate() + 10);

    // Format to YYYY-MM-DD
    const formattedMax = maxDate.toISOString().split('T')[0];

    const endDateInput = document.getElementById("end_date");
    endDateInput.setAttribute("max", formattedMax);

    // Optional: also update the end_date value if it's out of range
    if (new Date(endDateInput.value) > maxDate) {
      endDateInput.value = formattedMax;
    }
  });
</script>