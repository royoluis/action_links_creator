<h1>Module description and functionality</h1>
<p>This module allows you add custom buttons to create new nodes near "+Add content" button in Content Administration section.</p> 

![image](https://github.com/user-attachments/assets/f4d85849-ceb1-47a1-9c7b-2a5a24f7c9a3)
<h2>Example</h2>
<p>Imagine a group of post authors who create content every single day.</p> 
<p>They primarily use two specific Content Types ("Event" and "Multimedia") and would like to have two buttons to add content in a faster way.</p> 
<p>Let's get to work!</p>
<ol>
  <li>Go to <b>admin/config/development/action_list_creator</b> and check "Event" and "Multimedia".</li>
  
  ![image](https://github.com/user-attachments/assets/96bcea43-c9d5-4bce-9397-0f3b682648f2)

  <li>You can <b>change the link titles</b>. Let's set "Add Media" instead of "Add Multimedia" (default title).</li>

  ![image](https://github.com/user-attachments/assets/d4835ced-c946-4abb-a210-5460ff70497f)
  
  <li>Click on <b>"Save configuration"</b>-</li></br>
  
  <li>Both buttons are ready near "+Add content".</li>

  ![image](https://github.com/user-attachments/assets/b378bf92-3b54-4513-8179-91b95ebc8928)
</ol>
<h2>How to reorder our custom buttons</h2>
<p>It's very easy. Just go to <b>admin/config/development/action_list_creator</b> and configure the weight values as needed.</p>

![image](https://github.com/user-attachments/assets/c23c4143-245a-4335-90be-47c1647a1038)

<p>Weight one will be the first, then the second, and so on.</p>
<h2>How to delete the custom buttons we have created</h2>
<p>It's also very easy. Just go to <b>admin/config/development/action_list_creator</b>, uncheck "Event" and "Multimedia" and <b>save</b>.</p>
<p>Feel free to use and share this custom module.</p>
