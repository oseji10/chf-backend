<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Application Submitted</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;800&display=swap" rel="stylesheet">
  <style>
    * {
      font-family: 'Nunito', sans-serif;
      box-sizing: border-box;
    }

    body {
      background-color: #eee;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      width: 100%;
    }

    .heading {
      font-size: 20pt;
      color: #666;
    }

    .reset-wrapper {
      width: 95%;
      background-color: white;
      max-width: 600px;
      min-height: 20vh;
      padding: 1em;
      box-shadow: 0 0 10px rgba(100, 100, 100, .5);
      align-items: center;
      margin: 0 auto;
    }

    .title {
      margin-top: 1rem;
      text-align: center;
      font-size: 16pt;
      color: rgb(27, 133, 0);
      text-transform: uppercase;
    }

    p {
      color: #666;
    }

    h3 {
      text-align: left;
      font-size: 14pt;
      align-self: flex-start;
      text-transform: uppercase;
      font-weight: 900;
      letter-spacing: 4px;
      color: rgb(27, 133, 0);
    }

    .btn {
      display: block;
      text-align: center;
      font-size: 14pt;
      background-color: rgb(27, 133, 0);
      padding: .5em 1em;
      color: white;
      text-decoration: none;
    }

    .btn:hover {
      background-color: rgb(26, 83, 12);
    }

    .code {
      font-size: 16pt;
      letter-spacing: 2px;
    }

    .foot-note {
      font-size: 9pt;
      text-align: center;
      margin-top: 4em;
    }

    .d-block {
      display: block;
    }

    .div-flex {
      padding: 20px;
    }

    .logo {
      display: block;
      position: relative;
      margin: 0 auto;
      width: 80%;
      height: 70px;
      max-width: 150px;
    }

    .page-break {
      page-break-after: always;
    }
  </style>
</head>

<body>
  <div class="reset-wrapper">
    <div class="d-block">
      <img src="https://chf.emgeresources.com/images/formCoverLogo.png" alt="" class="logo">
      <div>
        <div class="d-block">
          <h1 class="title">&nbsp;</h1>
        </div>
        <div class="d-block">
          <h1 class="title">APPLICATION FOR CANCER HEALTH FUND</h1>
        </div>
        <div class="div-flex d-flex flex-column justify-content-start align-items-center">
          <table class="table table-sm table-bordered p-4">
            <thead>
              <tr>
                <th colspan="2">
                  <h3><strong>Patient Information </strong></h3>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>Name: </strong></td>
                <td>{{$applicationReview->user->first_name.' '.$applicationReview->user->last_name}}</td>
              </tr>
              <tr>
                <td><strong>CHF ID: </strong></td>
                <td>{{$applicationReview->patient->chf_id}}</td>
              </tr>
              <tr>
                <td><strong>Cancer type: </strong></td>
                <td>{{$applicationReview->patient->ailment->ailment_type}}</td>
              </tr>
              <tr>
                <td><strong>Cancer stage: </strong></td>
                <td>{{$applicationReview->patient->ailment_stage}}</td>
              </tr>
              <tr>
                <td><strong>Center of Excellence(hospital): </strong></td>
                <td colspan="3">{{$applicationReview->patient->coe->coe_name}}</td>
              </tr>
              <tr>
                <td><strong>State of Origin: </strong></td>
                <td>{{$applicationReview->patient->state->state}}</td>
              </tr>
              <tr>
                <td><strong>LGA of Origin: </strong></td>
                <td>{{$applicationReview->patient->state->lga($applicationReview->patient->lga_id)->lga}}</td>
              </tr>
              <tr>
                <td><strong>State of Residence: </strong></td>
                <td>{{$applicationReview->patient->stateOfResidence->state}}</td>
              </tr>
              <tr>
                <td><strong>Address: </strong></td>
                <td>{{$applicationReview->patient->address}}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="div-flex d-flex flex-column justify-content-start align-items-center">
          <table class="table table-sm table-bordered p-4">
            <thead>
              <tr>
                <th colspan="2">
                  <h3><strong>Personal Information </strong></h3>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>NHIS NO: </strong></td>
                <td>{{$applicationReview->personalInformation->nhis_no}}</td>
              </tr>
              <tr>
                <td><strong>CHF ID: </strong></td>
                <td>{{$applicationReview->personalInformation->gender}}</td>
              </tr>
              <tr>
                <td><strong>Age: </strong></td>
                <td>{{$applicationReview->personalInformation->age}}</td>
              </tr>
              <tr>
                <td><strong>Ethnicity: </strong></td>
                <td>{{$applicationReview->personalInformation->gender}}</td>
              </tr>
              <tr>
                <td><strong>Marital Status: </strong></td>
                <td>{{
                        $applicationReview->personalInformation->marital_status
                      }}
                </td>
              </tr>
              <tr>
                <td><strong>Number of children: </strong></td>
                <td>{{$applicationReview->personalInformation->no_of_children}}</td>
              </tr>
              <tr>
                <td><strong>Level of education: </strong></td>
                <td>{{$applicationReview->personalInformation->level_of_education}}</td>
              </tr>
              <tr>
                <td><strong>Religion: </strong></td>
                <td>{{$applicationReview->personalInformation->religion}}</td>
              </tr>
              <tr>
                <td><strong>Occupation: </strong></td>
                <td>{{$applicationReview->personalInformation->occupation}}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="div-flex d-flex flex-column justify-content-start align-items-center">
          <table class="table table-sm table-bordered p-4">
            <thead>
              <tr>
                <th colspan="2">
                  <h3><strong>Personal history </strong></h3>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>Average income earned per month: </strong></td>
                <td>{{$applicationReview->personalHistory->average_income_per_month}}</td>
              </tr>
              <tr>
                <td><strong>How many times do you eat on the average in a day?: </strong></td>
                <td>{{$applicationReview->personalHistory->average_eat_daily}}</td>
              </tr>
              <tr>
                <td><strong>Who provides the feeding?: </strong></td>
                <td>{{$applicationReview->personalHistory->who_provides_feeding}}</td>
              </tr>
              <tr>
                <td><strong>Do you have an accommodation?: </strong></td>
                <td>{{$applicationReview->personalHistory->have_accomodation}}</td>
              </tr>
              <tr>
                <td><strong>If yes, what type of accommodation? If no, choose others and enter NA: </strong></td>
                <td>{{$applicationReview->personalHistory->type_of_accomodation}}</td>
              </tr>
              <tr>
                <td><strong>How many good set of clothes do you have: </strong></td>
                <td>{{$applicationReview->personalHistory->no_of_good_set_of_cloths}}</td>
              </tr>
              <tr>
                <td><strong>How do you get them?: </strong></td>
                <td>{{$applicationReview->personalHistory->how_you_get_them}}</td>
              </tr>
              <tr>
                <td><strong>When you are ill, where do you receive care?: </strong></td>
                <td>{{$applicationReview->personalHistory->where_you_receive_care}}</td>
              </tr>
              <tr>
                <td><strong>Why do you choose the above mentioned care centre?: </strong></td>
                <td>{{$applicationReview->personalHistory->why_choose_center_above}}</td>
              </tr>
              <tr>
                <td><strong>Level of spousal/children support: </strong></td>
                <td>{{$applicationReview->personalHistory->level_of_spousal_support}}</td>
              </tr>
              <tr>
                <td><strong>Other significant source(s) of support?: </strong></td>
                <td>{{$applicationReview->personalHistory->other_support}}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="div-flex d-flex flex-column justify-content-start align-items-center">
          <table class="table table-sm table-bordered p-4">
            <thead>
              <tr>
                <th colspan="2">
                  <h3><strong>Family History </strong></h3>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>Family Setup: </strong></td>
                <td>{{$applicationReview->familyHistory->family_set_up}}</td>
              </tr>
              <tr>
                <td><strong>Family size (how many are you in the family?): </strong></td>
                <td>{{$applicationReview->familyHistory->family_size}}</td>
              </tr>
              <tr>
                <td><strong>Birth order (position in the family): </strong></td>
                <td>{{$applicationReview->familyHistory->birth_order}}</td>
              </tr>
              <tr>
                <td><strong>Fathers’ educational status: </strong></td>
                <td>{{$applicationReview->familyHistory->father_education_status}}</td>
              </tr>
              <tr>
                <td><strong>Mothers’ educational status: </strong></td>
                <td>{{$applicationReview->familyHistory->mother_education_status}}</td>
              </tr>
              <tr>
                <td><strong>Father’s occupation: </strong></td>
                <td>{{$applicationReview->familyHistory->fathers_occupation}}</td>
              </tr>
              <tr>
                <td><strong>Mother’s occupation: </strong></td>
                <td>{{$applicationReview->familyHistory->mothers_occupation}}</td>
              </tr>

              <tr>
                <td><strong>Family total income per month: </strong></td>
                <td>{{$applicationReview->familyHistory->family_total_income_month}}</td>
              </tr>
              <tr>
                <td><strong>Level of Family care/support: </strong></td>
                <td>{{$applicationReview->familyHistory->level_of_family_care}}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="div-flex d-flex flex-column justify-content-start align-items-center">
          <table class="table table-sm table-bordered p-4">
            <thead>
              <tr>
                <th colspan="2">
                  <h3><strong>Social Condition </strong></h3>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>Do you have running water in your house?: </strong></td>
                <td>{{$applicationReview->socialCondition->have_running_water}}</td>
              </tr>
              <tr>
                <td><strong>Type of toilet facility: </strong></td>
                <td>{{$applicationReview->socialCondition->type_of_toilet_facility}}</td>
              </tr>
              <tr>
                <td><strong>Do you have generator to power light in your house?: </strong></td>
                <td>{{$applicationReview->socialCondition->have_generator_solar}}</td>
              </tr>
              <tr>
                <td><strong>Means of transportation?: </strong></td>
                <td>{{$applicationReview->socialCondition->means_of_transportation}}</td>
              </tr>
              <tr>
                <td><strong>Do you have a handset?: </strong></td>
                <td>{{$applicationReview->socialCondition->have_mobile_phone}}</td>
              </tr>
              <tr>
                <td><strong>Do you have a handset?: </strong></td>
                <td>{{$applicationReview->socialCondition->how_maintain_phone_use}}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="div-flex d-flex flex-column justify-content-start align-items-center">
          <table class="table table-sm table-bordered p-4">
            <thead>
              <tr>
                <th colspan="2">
                  <h3><strong>Support Assessment </strong></h3>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>How often do you need financial assistance from other people to feed?: </strong></td>
                <td>{{$applicationReview->supportAssessment->feeding_assistance}}</td>
              </tr>
              <tr>
                <td><strong>How often do you need financial assistance from other people to treat yourself when you are ill?: </strong></td>
                <td>{{$applicationReview->supportAssessment->medical_assistance}}</td>
              </tr>
              <tr>
                <td><strong>How often do you need financial assistance from other people to pay house rent?: </strong></td>
                <td>{{$applicationReview->supportAssessment->rent_assistance}}</td>
              </tr>
              <tr>
                <td><strong>How often do you need financial assistance from other people to buy clothes?: </strong></td>
                <td>{{$applicationReview->supportAssessment->clothing_assistance}}</td>
              </tr>
              <tr>
                <td><strong>How often do you need financial assistance from other people for transportation? </strong></td>
                <td>{{$applicationReview->supportAssessment->transport_assistance}}</td>
              </tr>
              <tr>
                <td><strong>How often do you need financial assistance from other people to buy recharge card? </strong></td>
                <td>{{$applicationReview->supportAssessment->mobile_bill_assistance}}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="div-flex d-flex flex-column justify-content-start align-items-center">
          <table class="table table-sm table-bordered p-4">
            <thead>
              <tr>
                <th colspan="2">
                  <h3><strong>Next of kin </strong></h3>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>Full Name of next of kin: </strong></td>
                <td>{{$applicationReview->patient->nextOfKin->name}}</td>
              </tr>
              <tr>
                <td><strong>Relationship with next of kin: </strong></td>
                <td>{{$applicationReview->patient->nextOfKin->relationship}}</td>
              </tr>
              <tr>
                <td><strong>Email of next of kin: </strong></td>
                <td>{{$applicationReview->patient->nextOfKin->email}}</td>
              </tr>
              <tr>
                <td><strong>Phone number of next of kin: </strong></td>
                <td>{{$applicationReview->patient->nextOfKin->phone_number}}</td>
              </tr>
              <tr>
                <td><strong>Alternate Phone number of next of kin (if there is one): </strong></td>
                <td>{{$applicationReview->patient->nextOfKin->alternate_phone_number}}</td>
              </tr>
              <tr>
                <td><strong>State of residence: </strong></td>
                <td>{{$applicationReview->patient->nextOfKin->stateOfResidence->state}}</td>
              </tr>
              <tr>
                <td><strong>LGA of residence: </strong></td>
                <td>{{$applicationReview->patient->nextOfKin->stateOfResidence->lga($applicationReview->patient->nextOfKin->lga_of_residence)->lga}}</td>
              </tr>
              <tr>
                <td><strong>City: </strong></td>
                <td>{{$applicationReview->patient->nextOfKin->city}}</td>
              </tr>
              <tr>
                <td><strong>Address: </strong></td>
                <td>{{$applicationReview->patient->nextOfKin->address}}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <p><em>Your application to the CHF program has been submitted successfully and pending
            approval. You will get an approval or decline notification within 2 to 7 working days. For further details and enquiries, communicate with your choosen hospital (Center of Excellence) for advice. You can also leave a chat for CHF support team using the chat on CHF at <a href="https://chf.emgeresources.com">https://chf.emgeresources.com</a> .</em></p>
        <p class="foot-note"></p>
        <p class="foot-note">
          <span class="d-block">Federal Secretariat Complex, Phase III, Shehu Shagari Way, Central Business District. Abuja.</span>
          &copy; CHF 2021
        </p>
      </div>
</body>

</html>