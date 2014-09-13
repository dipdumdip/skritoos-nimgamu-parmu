<?php

// class StockLengthTest extends TestCase {

// 	/**
// 	 * A basic functional test example.
// 	 *
// 	 * @return void
// 	 */
//     /**
//      * company_name is required
//      */
//     public function testCompanyNameIsRequired()
//     {
//       // Create a new User
//       $company = new Company\Company;
//       $company->company_symbol = "gogog";
//       $company->description = "company_name is required";
//       $company->address = "lasas0-1,agibe, UK";
//       $company->created = "2014-09-09 00:00:00";
//       $company->view_count = "1";
     
//       // Company should not save
//       $this->assertFalse($company->save());
     
//       // Save the errors
//       $errors = $company->errors()->all();
     
//       // There should be 1 error
//       $this->assertCount(1, $errors);
     
//       // The company_name error should be set
//       $this->assertEquals($errors[0], "The company_name field is required.");
//     }


//     /**
//      * test sample is required
//      */
//   public function testSomethingIsTrue()
//     {
//         $this->assertTrue(true);
//     }

// }
