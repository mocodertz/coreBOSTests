<?php
/*************************************************************************************************
 * Copyright 2016 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Tests.
 * The MIT License (MIT)
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT
 * NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *************************************************************************************************/
class VTSimpleTemplateTest extends PHPUnit_Framework_TestCase {

	/**
	 * Method testRender
	 * @test
	 */
	public function testRender() {
		// Setup
		$entityId = '11x74';
		$util = new VTWorkflowUtils();
		$adminUser = $util->adminUser();
		$entityCache = new VTEntityCache($adminUser);
		// Constant string.
		$ct = new VTSimpleTemplate('Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto.');
		$expected = 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto.';
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals($expected, $actual, 'Constant String');
		// Account variables
		$ct = new VTSimpleTemplate('AccountId:$account_no - AccountName:$accountname');
		$expected = 'AccountId:ACC1 - AccountName:Chemex Labs Ltd';
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals($expected, $actual,'Account variables');
		// User variables
		$ct = new VTSimpleTemplate('$(assigned_user_id : (Users) email1)');
		$expected = 'noreply@tsolucio.com';
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals($expected, $actual,'User variables');
		// Member of
		$ct = new VTSimpleTemplate('$(account_id : (Accounts) accountname)');
		$expected = 'Rowley Schlimgen Inc';
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals($expected, $actual,'Member of variables');
		// Teardown
		$util->revertUser();
	}

	/**
	 * Method testRenderMultiline
	 * @test
	 */
	public function testRenderMultiline() {
		// Setup
		$entityId = '13x5142';
		$util = new VTWorkflowUtils();
		$adminUser = $util->adminUser();
		$entityCache = new VTEntityCache($adminUser);
		// Potenial Email
		$ct = new VTSimpleTemplate('<p>An Potential has been assigned to you on vtigerCRM<br />
Details of Potential are :<br />
<br />
Potential No:<b>$potential_no</b><br />
Potential Name:<b>$potentialname</b><br />
Amount:<b>$amount</b><br />
Expected Close Date:<b>$closingdate</b><br />
Type:<b>$opportunity_type</b><br />
<br />
$(related_to : (Accounts) accountname)</p>

<p>$(related_to : (Contacts) firstname)</p>

<p><br />
<br />
Thank You<br />
Admin</p>');
		$expected = '<p>An Potential has been assigned to you on vtigerCRM<br />
Details of Potential are :<br />
<br />
Potential No:<b>POT5</b><br />
Potential Name:<b>Non Magna Industries</b><br />
Amount:<b>80.786,00</b><br />
Expected Close Date:<b>06-10-2016</b><br />
Type:<b>--None--</b><br />
<br />
</p>

<p>Sol</p>

<p><br />
<br />
Thank You<br />
Admin</p>';
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals($expected, $actual,'Potential Email');
		// Teardown
		$util->revertUser();
	}

	/**
	 * Method testRenderCalendar
	 * @test
	 */
	public function testRenderCalendar() {
		// Setup
		$template = '$(assigned_user_id : (Users) first_name) $(assigned_user_id : (Users) last_name),
Activity Notification Details:
Subject : $subject
Start date and time : $date_start $time_start ($(general : (__VtigerMeta__) dbtimezone))
End date and time : $due_date $time_end ($(general : (__VtigerMeta__) dbtimezone))
Follow up date: $followupdt
Status : $eventstatus
Priority : $taskpriority
Related To : $(rel_id : (Leads) lastname)$(rel_id : (Leads) firstname)$(rel_id : (Accounts) accountname)$(rel_id : (Potentials) potentialname)$(rel_id : (HelpDesk) ticket_title)
Contacts List : $(cto_id : (Contacts) lastname) $(cto_id : (Contacts) firstname)
Location : $location';
		$entityId = '39x29198';
		$util = new VTWorkflowUtils();
		$adminUser = $util->adminUser();
		$entityCache = new VTEntityCache($adminUser);
		// Constant string.
		$ct = new VTSimpleTemplate($template);
		$expected = 'cbTest testes,
Activity Notification Details:
Subject : Zheng Zu
Start date and time : 07-07-2017 16:49:00 (UTC)
End date and time : 07-07-2017 17:50:00 (UTC)
Follow up date: 
Status : Planned
Priority : Medium
Related To : Ligula Inc.
Contacts List : Handrick Ora
Location : ';
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals($expected, $actual, 'Render cbCalendar email body');
		$entityId = '39x29396';
		$util = new VTWorkflowUtils();
		$adminUser = $util->adminUser();
		$entityCache = new VTEntityCache($adminUser);
		// Constant string.
		$expected = 'cbTest testymd,
Activity Notification Details:
Subject : Astrid Mordo
Start date and time : 2015-05-01 03:16:00 (UTC)
End date and time : 2015-05-01 10:38:00 (UTC)
Follow up date: 
Status : Planned
Priority : High
Related To : Sherpa Corp
Contacts List : Thro Coletta
Location : ';
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals($expected, $actual, 'Render cbCalendar email body');
		$entityId = '9x14737';
		$util = new VTWorkflowUtils();
		$adminUser = $util->adminUser();
		$entityCache = new VTEntityCache($adminUser);
		// Constant string.
		$expected = 'cbTest testmdy,
Activity Notification Details:
Subject : Salamandra
Start date and time : 03-31-2015 11:43:00 (UTC)
End date and time : 03-31-2015 12:16:00 (UTC)
Follow up date: 
Status : In Progress
Priority : Low
Related To : JuveraNickolas
Contacts List : Kloska Aaron
Location : Hamburg';
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals($expected, $actual, 'Render Calendar email body');
		$entityId = '39x14737';
		$util = new VTWorkflowUtils();
		$adminUser = $util->adminUser();
		$entityCache = new VTEntityCache($adminUser);
		// Constant string.
		$expected = 'cbTest testmdy,
Activity Notification Details:
Subject : Salamandra
Start date and time : 03-31-2015 11:43:00 (UTC)
End date and time : 03-31-2015 12:16:00 (UTC)
Follow up date: 
Status : In Progress
Priority : Low
Related To : JuveraNickolas
Contacts List : Kloska Aaron
Location : Hamburg';
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals($expected, $actual, 'Render Calendar email body');
		// Teardown
		$util->revertUser();
	}

	/**
	 * Method testMeta
	 * @test
	 */
	public function testMeta() {
		global $site_URL;
		// Setup
		$entityId = '12x1607';
		$util = new VTWorkflowUtils();
		$adminUser = $util->adminUser();
		$entityCache = new VTEntityCache($adminUser);
		// Detail View URL
		$ct = new VTSimpleTemplate('$(general : (__VtigerMeta__) crmdetailviewurl)');
		$expected = $site_URL.'/index.php?action=DetailView&module=Contacts&record=1607';
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals($expected, $actual, 'Detail View URL');
		// Today
		$ct = new VTSimpleTemplate('$(general : (__VtigerMeta__) date)');
		$expected = date('Y-m-d');
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals($expected, $actual,'Today');
		// Record ID
		$ct = new VTSimpleTemplate('$(general : (__VtigerMeta__) recordId)');
		$expected = '1607';
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals($expected, $actual,'Record ID');
		// Comments
		$ct = new VTSimpleTemplate('$(general : (__VtigerMeta__) comments)');
		$expected = '<div class="comments"><div class="commentdetails"><ul class="commentfields"><li class="commentcreator"> Administrator</li><li class="commentdate">2015-10-06 11:31:58</li><li class="commentcomment">turpis nec mauris blandit mattis. Cras eget nisi dictum augue malesuada malesuada. Integer id magna et ipsum cursus vestibulum. Mauris magna. Duis dignissim tempor arcu. Vestibulum ut eros non enim commodo hendrerit. Donec porttitor tellus non magna. Nam ligula elit, pretium et, rutrum non, hendrerit id, ante. Nunc mauris sapien, cursus in, hendrerit consectetuer, cursus et, magna. Praesent interdum ligula eu enim. Etiam imperdiet dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit, est ac facilisis facilisis, magna tellus faucibus leo, in lobortis tellus justo sit amet nulla. Donec non justo. Proin non massa non ante bibendum ullamcorper. Duis cursus, diam at pretium aliquet, metus urna convallis erat, eget tincidunt dui augue eu tellus. Phasellus elit pede, malesuada vel, venenatis vel, faucibus id, libero. Donec consectetuer mauris id sapien. Cras dolor dolor, tempus non, lacinia at, iaculis quis, pede.</li></ul></div><div class="commentdetails"><ul class="commentfields"><li class="commentcreator"> Administrator</li><li class="commentdate">2015-12-15 21:18:07</li><li class="commentcomment">nec metus facilisis lorem tristique aliquet. Phasellus fermentum convallis ligula. Donec luctus aliquet odio. Etiam ligula tortor, dictum eu, placerat eget, venenatis a, magna. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam laoreet, libero et tristique pellentesque, tellus sem mollis dui, in sodales elit erat vitae risus. Duis a mi fringilla mi lacinia mattis. Integer eu lacus. Quisque imperdiet, erat nonummy ultricies ornare, elit elit fermentum risus, at fringilla purus mauris a nunc. In at pede. Cras vulputate velit eu sem. Pellentesque ut ipsum ac mi eleifend egestas. Sed pharetra, felis eget varius ultrices, mauris ipsum porta elit, a feugiat tellus lorem eu metus. In lorem. Donec elementum, lorem ut aliquam iaculis, lacus pede sagittis augue, eu tempor</li></ul></div></div>';
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals($expected, $actual,'Comments');
		$ct = new VTSimpleTemplate('$(general : (__VtigerMeta__) comments_1d_text_comment)');
		$expected = 'nec metus facilisis lorem tristique aliquet. Phasellus fermentum convallis ligula. Donec luctus aliquet odio. Etiam ligula tortor, dictum eu, placerat eget, venenatis a, magna. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam laoreet, libero et tristique pellentesque, tellus sem mollis dui, in sodales elit erat vitae risus. Duis a mi fringilla mi lacinia mattis. Integer eu lacus. Quisque imperdiet, erat nonummy ultricies ornare, elit elit fermentum risus, at fringilla purus mauris a nunc. In at pede. Cras vulputate velit eu sem. Pellentesque ut ipsum ac mi eleifend egestas. Sed pharetra, felis eget varius ultrices, mauris ipsum porta elit, a feugiat tellus lorem eu metus. In lorem. Donec elementum, lorem ut aliquam iaculis, lacus pede sagittis augue, eu tempor';
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals($expected, $actual,'Comments 1d_text_comment');
		// Teardown
		$util->revertUser();
	}

	/**
	 * Method testFunctions
	 * @test
	 */
	public function testFunctions() {
		// Setup
		$entityId = '12x1607';
		$util = new VTWorkflowUtils();
		$adminUser = $util->adminUser();
		$entityCache = new VTEntityCache($adminUser);
		// Contact first name uppercase
		$ct = new VTSimpleTemplate('$(general : (__WorkflowFunction__) uppercase(firstname ) ) ');
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals('CORAZON', $actual, 'uppercase(firstname )');
		// uppercase string
		$ct = new VTSimpleTemplate('$(general : (__WorkflowFunction__) '."uppercase('firstname' ) ) ");
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals('FIRSTNAME', $actual,'uppercase string');
		// Formatted date
		$ct = new VTSimpleTemplate('$(general : (__WorkflowFunction__) '."format_date(get_date('today'),'Ymd') ) ");
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals(date('Ymd'), $actual,'Formatted date');
		// concat related info
		$ct = new VTSimpleTemplate('$(general : (__WorkflowFunction__) concat($(account_id : (Accounts) accountname),email ) ) ');
		$expected = 'Spieker Propertiescgrafenstein@gmail.com';
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals($expected, $actual,'concat related info');
		// concat related info with space
		$ct = new VTSimpleTemplate('$(general : (__WorkflowFunction__) concat($(account_id : (Accounts) accountname),'."' - ', email ) ) ");
		$expected = 'Spieker Properties - cgrafenstein@gmail.com';
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals($expected, $actual,'concat related info');
		// Access current user name in full
		$ct = new VTSimpleTemplate('$(general : (__WorkflowFunction__) '."getCurrentUserName('full') ) ");
		$expected = 'Administrator';
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals($expected, $actual,'get full user name');
		// Access current user email
		$ct = new VTSimpleTemplate('$(general : (__WorkflowFunction__) '."getCurrentUserField('email1') ) ");
		$expected = 'noreply@tsolucio.com';
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals($expected, $actual,'get user email');
		// accountname Contact first name uppercase
		$ct = new VTSimpleTemplate('$(account_id : (Accounts) accountname) $(general : (__WorkflowFunction__) uppercase(firstname ) ) ');
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals('Spieker Properties CORAZON', $actual, 'uppercase(firstname )');
		// Contact first name uppercase + accountname
		$ct = new VTSimpleTemplate('$(general : (__WorkflowFunction__) uppercase(firstname ) )  $(account_id : (Accounts) accountname)');
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals('CORAZON Spieker Properties', $actual, 'uppercase(firstname )');
		// uppercase string + accountname
		$ct = new VTSimpleTemplate('$(general : (__WorkflowFunction__) '."uppercase('firstname' ) )  $(account_id : (Accounts) accountname)");
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals('FIRSTNAME Spieker Properties', $actual,'uppercase string');
		// Contact first name uppercase + accountname
		$ct = new VTSimpleTemplate('$(general : (__WorkflowFunction__) uppercase(firstname) )  $(account_id : (Accounts) accountname)');
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals('CORAZON Spieker Properties', $actual, 'uppercase(firstname )');
		// uppercase string + accountname
		$ct = new VTSimpleTemplate('$(general : (__WorkflowFunction__) '."uppercase('firstname') )  $(account_id : (Accounts) accountname)");
		$actual = $ct->render($entityCache, $entityId);
		$this->assertEquals('FIRSTNAME Spieker Properties', $actual,'uppercase string');
		// Teardown
		$util->revertUser();
	}

}
